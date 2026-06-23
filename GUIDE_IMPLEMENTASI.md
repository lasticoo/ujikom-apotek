# 💻 GUIDE IMPLEMENTASI & BEST PRACTICES - UJIKOM APOTEK APP

**Referensi untuk developer dalam mengimplementasi features baru atau modify existing features**

---

## TABLE OF CONTENTS

1. [Setup & Development Environment](#1-setup--development-environment)
2. [Database Operations Best Practices](#2-database-operations-best-practices)
3. [Controller Development Pattern](#3-controller-development-pattern)
4. [Model Relationships & Methods](#4-model-relationships--methods)
5. [Form Validation](#5-form-validation)
6. [Authorization & Middleware](#6-authorization--middleware)
7. [Common CRUD Implementation](#7-common-crud-implementation)
8. [Error Handling](#8-error-handling)
9. [Testing](#9-testing)
10. [Performance Optimization](#10-performance-optimization)

---

## 1. SETUP & DEVELOPMENT ENVIRONMENT

### 🚀 Project Setup Commands

```bash
# Clone repository (if applicable)
cd c:\laragon\www\Ujikom-apotek-app

# Run migrations
php artisan migrate:fresh --seed

# Start development server
php artisan serve

# Compile assets
npm run dev

# Run tests
php artisan test
```

### 📋 Environment Configuration

File: `.env`

```env
APP_NAME="Ujikom Apotek"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ujikom_apotek
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
```

### 🛠️ Development Tools

```bash
# Clear cache
php artisan cache:clear

# Migrate specific
php artisan migrate:specific

# Rollback last migration
php artisan migrate:rollback

# Fresh migrate dengan seed
php artisan migrate:fresh --seed

# Tinker (interactive shell)
php artisan tinker

# Generate model with migration
php artisan make:model ModelName -m

# Generate controller with resource
php artisan make:controller ControllerName --resource
```

---

## 2. DATABASE OPERATIONS BEST PRACTICES

### ✅ Atomic Transactions

**ALWAYS gunakan DB::transaction() untuk multi-step operations:**

```php
use Illuminate\Support\Facades\DB;

public function store(Request $request)
{
    try {
        DB::transaction(function () use ($request) {
            // Step 1: Create main record
            $penjualan = Penjualan::create([
                'nota' => $this->generateNota(),
                'tgl_nota' => $request->tgl_nota,
                // ... fields
            ]);

            // Step 2: Create related records
            foreach ($request->items as $item) {
                PenjualanDetail::create([
                    'nota' => $penjualan->nota,
                    'kd_obat' => $item['kd_obat'],
                    'jumlah' => $item['jumlah'],
                ]);

                // Step 3: Update inventory
                $obat = Obat::findOrFail($item['kd_obat']);
                if ($obat->stok < $item['jumlah']) {
                    throw new \Exception("Stok tidak cukup");
                }
                $obat->decrement('stok', $item['jumlah']);
            }
        });

        return response()->json(['status' => 'success']);

    } catch (\Exception $e) {
        // Transaction automatically rolled back
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 422);
    }
}
```

**Why?**
- Jika ada error di step 2, step 1 otomatis di-rollback
- Memastikan data consistency
- Prevent partial updates

---

### ✅ Eager Loading (Avoid N+1 Query)

**❌ BAD - N+1 Query Problem:**

```php
// Controller
$penjualans = Penjualan::all(); // 1 query

// View
@foreach($penjualans as $penjualan)
    {{ $penjualan->pelanggan->nm_pelanggan }}  // N queries (per item)
@endforeach
// Total: 1 + N queries
```

**✅ GOOD - Use eager loading:**

```php
// Controller
$penjualans = Penjualan::with(['pelanggan', 'details.obat'])->get();
// Total: 3 queries regardless of how many penjualans

// View
@foreach($penjualans as $penjualan)
    {{ $penjualan->pelanggan->nm_pelanggan }}  // Already loaded
@endforeach
```

---

### ✅ Stock Validation Before Decrement

```php
// ALWAYS check stock before decrement
$obat = Obat::findOrFail($kd_obat);

// Check 1: Stock exists
if (!$obat) {
    throw new \Exception("Obat tidak ditemukan");
}

// Check 2: Stock sufficient
if ($obat->stok < $qty) {
    throw new \Exception(
        "Stok {$obat->nm_obat} tidak cukup. Tersedia: {$obat->stok}"
    );
}

// Check 3: Status aktif
if ($obat->status !== 'aktif') {
    throw new \Exception(
        "Obat {$obat->nm_obat} tidak dapat dijual (status: {$obat->status})"
    );
}

// Safe to decrement
$obat->decrement('stok', $qty);
```

---

### ✅ Using Model Scopes

**Define in Model:**

```php
namespace App\Models;

class Obat extends Model
{
    // Scope untuk status aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Scope untuk expired
    public function scopeKadaluarsa($query)
    {
        return $query->whereDate('tgl_kadaluarsa', '<=', today());
    }

    // Scope untuk stock > 0
    public function scopeAvailable($query)
    {
        return $query->where('stok', '>', 0);
    }

    // Chaining multiple scopes
    public function scopeReadyToSell($query)
    {
        return $query->aktif()->available();
    }
}
```

**Use in Controller:**

```php
// Single scope
$obats = Obat::aktif()->get();

// Multiple scopes
$obats = Obat::aktif()->available()->orderBy('nm_obat')->get();

// Chain with other conditions
$obats = Obat::readyToSell()
    ->where('id_kategori', $category_id)
    ->paginate(15);
```

---

## 3. CONTROLLER DEVELOPMENT PATTERN

### 📝 Standard Resource Controller Pattern

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Models\Obat;
use App\Models\KategoriObat;
use App\Models\Suplier;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ObatController
{
    /**
     * Show listing view
     * Route: GET /admin/obat
     */
    public function index(Request $request): View
    {
        // Query dengan eager loading
        $query = Obat::with(['kategori', 'suplier']);

        // Apply search filter
        if ($search = $request->search) {
            $query->where('nm_obat', 'like', "%{$search}%")
                  ->orWhere('kd_obat', 'like', "%{$search}%");
        }

        // Apply status filter
        if ($status = $request->status) {
            $query->where('status', $status);
        }

        // Paginate and maintain query string
        $obats = $query->orderBy('nm_obat')
                       ->paginate(15)
                       ->withQueryString();

        return view('admin.obat.index', compact('obats'));
    }

    /**
     * Show creation form
     * Route: GET /admin/obat/create
     */
    public function create(): View
    {
        $kategoris = KategoriObat::orderBy('nm_kategori')->get();
        $supliers = Suplier::orderBy('nm_suplier')->get();
        $kd_obat = Obat::generateKdObat(); // Auto-generated code

        return view('admin.obat.create', compact('kategoris', 'supliers', 'kd_obat'));
    }

    /**
     * Store new record to database
     * Route: POST /admin/obat
     */
    public function store(Request $request): RedirectResponse
    {
        // Generate automatic code
        $kd_obat = Obat::generateKdObat();
        $request->merge(['kd_obat' => $kd_obat]);

        // Validate input
        $validated = $request->validate([
            'nm_obat' => 'required|string|max:100',
            'id_kategori' => 'nullable|exists:kategori_obats,id',
            'satuan' => 'nullable|string|max:50',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0|gte:harga_beli',
            'tgl_kadaluarsa' => 'nullable|date|after:today',
            'kd_suplier' => 'nullable|exists:supliers,kd_suplier',
            'foto_obat' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('foto_obat')) {
            $validated['foto_obat'] = $request->file('foto_obat')
                ->store('obat_fotos', 'public');
        }

        // Create record
        Obat::create($validated);

        // Redirect with success message
        return redirect()->route('admin.obat.index')
                       ->with('success', 'Obat berhasil ditambahkan');
    }

    /**
     * Show single record detail
     * Route: GET /admin/obat/{id}
     */
    public function show(Obat $obat): View
    {
        return view('admin.obat.show', compact('obat'));
    }

    /**
     * Show edit form
     * Route: GET /admin/obat/{id}/edit
     */
    public function edit(Obat $obat): View
    {
        $kategoris = KategoriObat::orderBy('nm_kategori')->get();
        $supliers = Suplier::orderBy('nm_suplier')->get();

        return view('admin.obat.edit', compact('obat', 'kategoris', 'supliers'));
    }

    /**
     * Update record in database
     * Route: PATCH /admin/obat/{id}
     */
    public function update(Request $request, Obat $obat): RedirectResponse
    {
        $validated = $request->validate([
            'nm_obat' => 'required|string|max:100',
            'id_kategori' => 'nullable|exists:kategori_obats,id',
            'satuan' => 'nullable|string|max:50',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0|gte:harga_beli',
            'tgl_kadaluarsa' => 'nullable|date',
            'kd_suplier' => 'nullable|exists:supliers,kd_suplier',
            'foto_obat' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle file upload (delete old if new uploaded)
        if ($request->hasFile('foto_obat')) {
            if ($obat->foto_obat) {
                \Storage::disk('public')->delete($obat->foto_obat);
            }
            $validated['foto_obat'] = $request->file('foto_obat')
                ->store('obat_fotos', 'public');
        }

        $obat->update($validated);

        return redirect()->route('admin.obat.show', $obat->kd_obat)
                       ->with('success', 'Obat berhasil diperbarui');
    }

    /**
     * Delete record from database
     * Route: DELETE /admin/obat/{id}
     */
    public function destroy(Obat $obat): RedirectResponse
    {
        // Check if has related records
        if ($obat->penjualanDetails()->count() > 0) {
            return back()->withErrors([
                'error' => 'Tidak dapat menghapus obat yang sudah terjual'
            ]);
        }

        // Soft delete (mark as nonaktif)
        $obat->update(['status' => 'nonaktif']);

        // Or hard delete (not recommended)
        // $obat->delete();

        return redirect()->route('admin.obat.index')
                       ->with('success', 'Obat berhasil dihapus');
    }
}
```

---

## 4. MODEL RELATIONSHIPS & METHODS

### 📌 Define Relationships

```php
<?php

namespace App\Models;

class Penjualan extends Model
{
    // Relationship: Many Penjualan belong to one Pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'kd_pelanggan');
    }

    // Relationship: Many Penjualan belong to one User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relationship: One Penjualan has Many PenjualanDetails
    public function details()
    {
        return $this->hasMany(PenjualanDetail::class, 'nota');
    }

    // Custom method: Calculate total
    public function getTotal()
    {
        return $this->details->sum(function ($detail) {
            return $detail->jumlah * $detail->obat->harga_jual;
        }) - $this->diskon;
    }

    // Custom method: Get grand total (with tax if any)
    public function getGrandTotal()
    {
        $subtotal = $this->getTotal();
        $tax = $subtotal * 0.10; // 10% tax
        return $subtotal + $tax;
    }
}
```

### 📌 Using Relationships in Views

```blade
<!-- Access related data -->
<p>Pelanggan: {{ $penjualan->pelanggan->nm_pelanggan }}</p>
<p>Apoteker: {{ $penjualan->user->nama }}</p>

<!-- Loop through details -->
@foreach($penjualan->details as $detail)
    <tr>
        <td>{{ $detail->obat->nm_obat }}</td>
        <td>{{ $detail->jumlah }}</td>
        <td>Rp {{ number_format($detail->obat->harga_jual, 0) }}</td>
        <td>Rp {{ number_format($detail->subtotal(), 0) }}</td>
    </tr>
@endforeach

<!-- Call custom methods -->
<p>Total: Rp {{ number_format($penjualan->getTotal(), 0) }}</p>
```

---

## 5. FORM VALIDATION

### ✅ Validation Rules Reference

```php
// String validations
'nama' => 'required|string|max:100|min:3'

// Email validation
'email' => 'required|email|unique:users,email'
// Except current record:
'email' => 'required|email|unique:users,email,' . $user->id

// Numeric validation
'harga' => 'required|numeric|min:0|max:999999.99'
'qty' => 'required|integer|min:1|max:1000'

// Date validation
'tgl_nota' => 'required|date'
'tgl_kadaluarsa' => 'nullable|date|after:today'
'tgl_mulai' => 'required|date|before_or_equal:tgl_akhir'

// File validation
'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048'
'bukti' => 'nullable|file|mimes:pdf,jpg,png|max:5120'

// Relationship validation
'kd_pelanggan' => 'nullable|exists:pelanggans,kd_pelanggan'
'id_user' => 'required|exists:users,id'

// Array validation
'items' => 'required|array|min:1|max:50'
'items.*.kd_obat' => 'required|exists:obats,kd_obat'
'items.*.jumlah' => 'required|integer|min:1'

// Custom validation
'harga_jual' => 'required|numeric|gte:harga_beli' // >= harga_beli
```

### ✅ Custom Validation Rules

```php
// In controller
$request->validate([
    'stok' => 'required|integer|min:0|custom:check_stock_limit',
]);

// Define custom rule in AppServiceProvider or dedicated file
Validator::extend('check_stock_limit', function ($attribute, $value, $parameters, $validator) {
    // Custom logic
    return $value <= 10000; // Max stock: 10000
});

// With message
Validator::replacer('check_stock_limit', function ($message, $attribute, $rule, $parameters) {
    return str_replace(':attribute', $attribute, 'Stok tidak boleh melebihi 10000');
});
```

---

## 6. AUTHORIZATION & MIDDLEWARE

### ✅ Protect Routes with Middleware

```php
// routes/web.php

// Admin only
Route::middleware(['auth:web', 'role:admin'])
    ->prefix('admin')
    ->group(function () {
        Route::resource('obat', Admin\ObatController::class);
    });

// Apoteker only
Route::middleware(['auth:web', 'role:apoteker'])
    ->prefix('apoteker')
    ->group(function () {
        Route::resource('penjualan', Apoteker\PenjualanController::class);
    });

// Pelanggan only
Route::middleware(['pelanggan.auth'])
    ->prefix('pelanggan')
    ->group(function () {
        Route::post('cart/add/{obat}', [CartController::class, 'add']);
    });
```

### ✅ Check Authorization in Controller

```php
public function destroy(Obat $obat)
{
    // Check if current user is admin
    if (Auth::guard('web')->user()->role !== 'admin') {
        abort(403, 'Unauthorized');
    }

    // Or use policy
    // $this->authorize('delete', $obat);

    $obat->delete();
}
```

### ✅ Check Authorization in Blade View

```blade
@if(Auth::guard('web')->check() && Auth::guard('web')->user()->isAdmin())
    <button>Delete</button>
@endif

@if(Auth::guard('pelanggan')->check())
    <p>Welcome, {{ Auth::guard('pelanggan')->user()->pelanggan->nm_pelanggan }}</p>
@endif
```

---

## 7. COMMON CRUD IMPLEMENTATION

### Template: Simple CRUD

```php
// 1. LIST VIEW
public function index(Request $request): View
{
    $items = YourModel::with('relationships')
        ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
        ->paginate(15);

    return view('items.index', compact('items'));
}

// 2. CREATE FORM
public function create(): View
{
    return view('items.create');
}

// 3. STORE
public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([...]);
    YourModel::create($validated);

    return redirect()->route('items.index')
                   ->with('success', 'Item created');
}

// 4. SHOW DETAIL
public function show(YourModel $item): View
{
    return view('items.show', compact('item'));
}

// 5. EDIT FORM
public function edit(YourModel $item): View
{
    return view('items.edit', compact('item'));
}

// 6. UPDATE
public function update(Request $request, YourModel $item): RedirectResponse
{
    $validated = $request->validate([...]);
    $item->update($validated);

    return redirect()->route('items.show', $item->id)
                   ->with('success', 'Item updated');
}

// 7. DELETE
public function destroy(YourModel $item): RedirectResponse
{
    $item->delete();

    return redirect()->route('items.index')
                   ->with('success', 'Item deleted');
}
```

---

## 8. ERROR HANDLING

### ✅ Try-Catch with Meaningful Messages

```php
try {
    DB::transaction(function () {
        // Database operations
    });

    return response()->json([
        'status' => 'success',
        'message' => 'Operation completed successfully'
    ]);

} catch (\Illuminate\Validation\ValidationException $e) {
    return response()->json([
        'status' => 'validation_error',
        'errors' => $e->errors()
    ], 422);

} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
    return response()->json([
        'status' => 'error',
        'message' => 'Resource not found'
    ], 404);

} catch (\Exception $e) {
    \Log::error('Unexpected error: ' . $e->getMessage());

    return response()->json([
        'status' => 'error',
        'message' => 'An unexpected error occurred'
    ], 500);
}
```

### ✅ Handle Relationship Errors

```php
// Prevent cascade delete
public function delete()
{
    if ($this->orders()->exists()) {
        return back()->withErrors([
            'error' => 'Cannot delete customer with existing orders'
        ]);
    }

    $this->forceDelete();
}
```

---

## 9. TESTING

### ✅ Create Tests

```php
// Generate test file
php artisan make:test Penjualan/CreatePenjualanTest

// tests/Feature/Penjualan/CreatePenjualanTest.php
<?php

namespace Tests\Feature\Penjualan;

use App\Models\User;
use App\Models\Obat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatePenjualanTest extends TestCase
{
    use RefreshDatabase;

    public function test_apoteker_can_create_penjualan()
    {
        // Setup
        $apoteker = User::factory()->create(['role' => 'apoteker']);
        $obat = Obat::factory()->create(['stok' => 100]);

        // Act & Assert
        $response = $this->actingAs($apoteker)
            ->post('/apoteker/penjualan', [
                'tgl_nota' => now()->toDateString(),
                'items' => [
                    ['kd_obat' => $obat->kd_obat, 'jumlah' => 5]
                ]
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('penjualans', [...]);
        $this->assertEquals(95, $obat->fresh()->stok);
    }

    public function test_cannot_sell_more_than_available_stock()
    {
        $apoteker = User::factory()->create(['role' => 'apoteker']);
        $obat = Obat::factory()->create(['stok' => 5]);

        $response = $this->actingAs($apoteker)
            ->post('/apoteker/penjualan', [
                'tgl_nota' => now()->toDateString(),
                'items' => [
                    ['kd_obat' => $obat->kd_obat, 'jumlah' => 10]
                ]
            ]);

        $response->assertSessionHasErrors();
    }
}
```

### ✅ Run Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/Penjualan/CreatePenjualanTest.php

# Run with coverage
php artisan test --coverage

# Run with specific method
php artisan test --filter=test_apoteker_can_create_penjualan
```

---

## 10. PERFORMANCE OPTIMIZATION

### ⚡ Database Query Optimization

```php
// ❌ Bad: N+1 query
$penjualans = Penjualan::all();
foreach ($penjualans as $p) {
    echo $p->user->nama; // N queries
}

// ✅ Good: Eager loading
$penjualans = Penjualan::with('user')->get(); // 2 queries

// ✅ Best: Nested eager loading
$penjualans = Penjualan::with([
    'user',
    'pelanggan',
    'details.obat.kategori',
    'details.obat.suplier'
])->get();
```

### ⚡ Pagination for Large Results

```php
// Always paginate large result sets
$obats = Obat::paginate(15); // Not: ->get()

// In view
{{ $obats->links() }}

// Add query string preservation
$obats->withQueryString()
```

### ⚡ Caching

```php
// Cache frequently accessed data
$categories = \Cache::remember('categories', 60 * 60 * 24, function () {
    return KategoriObat::all();
});

// Clear cache when data changes
public function store(Request $request)
{
    KategoriObat::create($request->validated());
    \Cache::forget('categories');
}
```

### ⚡ Database Indexes

```php
// In migration
Schema::create('obats', function (Blueprint $table) {
    $table->string('kd_obat', 20)->primary();
    $table->string('nm_obat'); // Add index for search
    $table->index('nm_obat'); // ← Index for WHERE clause
    $table->index('status'); // ← Index for filter
    $table->index('id_kategori'); // ← Index for FK
    $table->index('tgl_kadaluarsa'); // ← Index for date range
});
```

---

## 📌 QUICK REFERENCE CHECKLIST

### When Creating New Feature:

- [ ] Create migration
- [ ] Create model with relationships
- [ ] Create controller with CRUD methods
- [ ] Create validation rules
- [ ] Create views (blade templates)
- [ ] Add routes
- [ ] Add middleware/authorization
- [ ] Test functionality
- [ ] Add error handling
- [ ] Test with invalid input

### Code Quality Checklist:

- [ ] Use meaningful variable/function names
- [ ] Add comments for complex logic
- [ ] Use type hints (return types & parameters)
- [ ] Handle exceptions properly
- [ ] Validate all input
- [ ] Use eager loading (with())
- [ ] Use transactions for multi-step operations
- [ ] Check authorization on every action
- [ ] Log important operations
- [ ] Write tests

### Security Checklist:

- [ ] Validate all user input
- [ ] Sanitize output in views
- [ ] Use CSRF tokens in forms
- [ ] Hash passwords
- [ ] Check authorization before action
- [ ] Use prepared statements (Eloquent ORM)
- [ ] Escape data in queries
- [ ] Log sensitive operations
- [ ] Rate limit endpoints if needed
- [ ] Validate file uploads

---

## 📚 USEFUL LARAVEL RESOURCES

- Laravel Documentation: https://laravel.com/docs
- Eloquent ORM: https://laravel.com/docs/eloquent
- Query Builder: https://laravel.com/docs/queries
- Blade Templates: https://laravel.com/docs/blade
- Validation: https://laravel.com/docs/validation

---

**Happy coding! 🚀**

