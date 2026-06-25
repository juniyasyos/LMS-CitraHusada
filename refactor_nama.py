import os
import re

directories_to_scan = ['app', 'resources', 'routes', 'config']

replacements = [
    (r'\$user->nama\b', '$user->name'),
    (r'\$item->user->nama\b', '$item->user->name'),
    (r'\$log->user->nama\b', '$log->user->name'),
    (r'\$report->user->nama\b', '$report->user->name'),
    (r'\$peserta->user->nama\b', '$peserta->user->name'),
    (r'\$peserta->nama\b', '$peserta->name'),
    (r'\$pengguna->nama\b', '$pengguna->name'),
    (r'\$murid->nama\b', '$murid->name'),
    (r'users\.nama\b', 'users.name'),
    (r'user:user_id,nama\b', 'user:user_id,name'),
    (r"'nama' => 'required", "'name' => 'required"),
    (r"'nama' => \$request->nama", "'name' => $request->name"),
    (r"\$request->nama\b", "$request->name"),
    (r"->where\('nama'", "->where('name'"),
    (r"->orWhere\('nama'", "->orWhere('name'"),
    (r"->orderBy\('nama'", "->orderBy('name'"),
    (r"->orderBy\('users\.nama'", "->orderBy('users.name'"),
    (r"->groupBy\('users\.nama'", "->groupBy('users.name'"),
    (r"->select\('users\.user_id', 'users\.nama'", "->select('users.user_id', 'users.name'"),
    (r"->pluck\('nama'", "->pluck('name'"),
    (r"->pluck\('nama'", "->pluck('name'"),
    (r"\[\'nama\'\]", "['name']"),
    (r"\[\"nama\"\]", "[\"name\"]"),
    (r"user->nama", "user->name"),
    (r"user\.nama", "user.name"),
    (r"auth\(\)->user\(\)->nama", "auth()->user()->name"),
    (r"Auth::user\(\)->nama", "Auth::user()->name"),
]

for directory in directories_to_scan:
    for root, _, files in os.walk(directory):
        for file in files:
            if file.endswith('.php') or file.endswith('.vue') or file.endswith('.js'):
                filepath = os.path.join(root, file)
                with open(filepath, 'r', encoding='utf-8') as f:
                    content = f.read()

                new_content = content
                for pattern, replacement in replacements:
                    new_content = re.sub(pattern, replacement, new_content)

                if content != new_content:
                    with open(filepath, 'w', encoding='utf-8') as f:
                        f.write(new_content)
                    print(f"Updated: {filepath}")
