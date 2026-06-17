<?php

namespace App\Http\Controllers;

use App\Models\Infografis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminBannerController extends Controller
{
    public function index()
    {
        $data = Infografis::query()
            ->where('tahun_kinerja', getTahunKinerja())
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validated = $this->validateBanner($request);
        $validated['tahun_kinerja'] = getTahunKinerja();

        if ($request->hasFile('gambar')) {
            $validated['gambar_url'] = $this->storeFile($request, 'gambar');
        }

        $banner = Infografis::query()->create($validated);

        return response()->json($banner, 201);
    }

    public function show($bannerId)
    {
        $banner = $this->findBanner($bannerId);

        return response()->json($banner);
    }

    public function update(Request $request, $bannerId)
    {
        $banner = $this->findBanner($bannerId);
        $validated = $this->validateBanner($request, $banner);
        $validated['tahun_kinerja'] = getTahunKinerja();

        if ($request->hasFile('gambar')) {
            $this->deleteStoredFile($banner->gambar_url);
            $validated['gambar_url'] = $this->storeFile($request, 'gambar');
        }

        $banner->update($validated);

        return response()->json($banner);
    }

    public function destroy($bannerId)
    {
        $banner = $this->findBanner($bannerId);

        $this->deleteStoredFile($banner->gambar_url);

        $banner->delete();

        return response()->json();
    }

    private function findBanner($bannerId): Infografis
    {
        if (! is_numeric($bannerId)) {
            abort(404, 'Banner tidak ditemukan.');
        }

        return Infografis::query()->whereKey($bannerId)->firstOrFail();
    }

    private function validateBanner(Request $request, ?Infografis $banner = null): array
    {
        $rules = [
            'judul' => ['required', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0'],
            'gambar' => [$banner ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];

        $validated = $request->validate($rules);
        unset($validated['gambar']);

        return $validated;
    }

    private function storeFile(Request $request, string $field): string
    {
        $file = $request->file($field);
        $fileName = Str::uuid().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs('banner', $fileName);

        return Storage::url($path);
    }

    private function deleteStoredFile(?string $url): void
    {
        if (! $url) {
            return;
        }

        $path = parse_url($url, PHP_URL_PATH) ?: $url;

        if (! Str::startsWith($path, '/storage/')) {
            return;
        }

        Storage::delete(Str::after($path, '/storage/'));
    }
}
