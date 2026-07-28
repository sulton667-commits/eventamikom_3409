<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    protected CloudinaryService $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $partners = Partner::when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('category', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('admin.partners.index', compact('partners', 'search'));
    }

    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'website_url' => 'nullable|url|max:255',
            'status' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,gif|max:2048'
        ]);

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $this->cloudinary->upload(
                $request->file('logo')->getRealPath(),
                'partners'
            );
        }
        unset($data['logo']);

        Partner::create($data);

        return redirect()->route('admin.partners.index')->with('success', 'Data Partner berhasil ditambahkan.');
    }

    public function edit(Partner $partner)
    {
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'website_url' => 'nullable|url|max:255',
            'status' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,gif|max:2048'
        ]);

        if ($request->hasFile('logo')) {
            // Hapus logo lama dari Cloudinary jika berupa URL Cloudinary
            if ($partner->logo_path && str_starts_with($partner->logo_path, 'http')) {
                $this->cloudinary->delete($partner->logo_path);
            }
            $data['logo_path'] = $this->cloudinary->upload(
                $request->file('logo')->getRealPath(),
                'partners'
            );
        }
        unset($data['logo']);

        $partner->update($data);

        return redirect()->route('admin.partners.index')->with('success', 'Data Partner berhasil diperbarui.');
    }

    public function destroy(Partner $partner)
    {
        // Hapus logo dari Cloudinary jika berupa URL Cloudinary
        if ($partner->logo_path && str_starts_with($partner->logo_path, 'http')) {
            $this->cloudinary->delete($partner->logo_path);
        }

        $partner->delete();
        return redirect()->route('admin.partners.index')->with('success', 'Data Partner berhasil dihapus.');
    }
}
