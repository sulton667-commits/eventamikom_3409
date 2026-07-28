@extends('layouts.admin')

@section('page_title', 'Kelola Admin')
@section('page_subtitle', 'Manajemen daftar pengguna dan pengelola sistem admin')

@section('content')
<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="p-8 border-b flex flex-col sm:flex-row justify-between items-center gap-4">
        <div>
            <h3 class="font-black text-xl text-slate-900">Daftar Admin</h3>
            <p class="text-xs text-slate-400 mt-1">Kelola hak akses dan akun administrator</p>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex-1 sm:w-64">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama / email..." class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-indigo-600">
            </form>
            <a href="{{ route('admin.users.create') }}" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-200 hover:bg-indigo-700 transition whitespace-nowrap">
                + Tambah Admin
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4">Pengguna</th>
                    <th class="px-8 py-4">Email</th>
                    <th class="px-8 py-4">Role</th>
                    <th class="px-8 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y border-t">
                @forelse($admins as $admin)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-8 py-6 flex items-center gap-4">
                            <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center font-bold text-sm shadow-sm">
                                {{ strtoupper(substr($admin->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-bold text-sm text-slate-900">{{ $admin->name }}</p>
                                <p class="text-xs text-slate-400">Dibuat: {{ $admin->created_at ? $admin->created_at->format('d M Y') : '-' }}</p>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-sm font-medium text-slate-600">
                            {{ $admin->email }}
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg text-xs font-bold uppercase">
                                {{ $admin->role ?? 'admin' }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.users.edit', $admin->id) }}" class="px-3 py-1.5 bg-amber-50 text-amber-600 rounded-lg text-xs font-bold hover:bg-amber-100 transition">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.users.destroy', $admin->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus admin ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-600 rounded-lg text-xs font-bold hover:bg-rose-100 transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-8 py-12 text-center text-slate-400 text-sm">
                            Belum ada akun admin.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($admins->hasPages())
        <div class="p-6 border-t">
            {{ $admins->links() }}
        </div>
    @endif
</div>
@endsection
