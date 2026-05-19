@extends('components.layout')

@section('title', 'API Documentation')

@section('content')
    @include('components.header-superadmin', ['title' => 'API Documentation'])

    <main class="min-h-screen bg-slate-50 dark:bg-slate-950 px-4 py-6 lg:px-10 lg:py-8">
        <div class="mx-auto max-w-7xl space-y-6">
            <section class="rounded-3xl border border-slate-200/80 bg-white/95 p-6 shadow-sm dark:border-slate-700/70 dark:bg-slate-900/95">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-slate-500">Developer Resources</p>
                        <h2 class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">API Documentation</h2>
                        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-300">
                            Halaman dokumentasi API tersembunyi untuk Superadmin. Akses hanya tersedia bila Anda telah login dan memiliki role Superadmin.
                        </p>
                    </div>
                    <div class="rounded-3xl bg-slate-100 p-4 text-slate-700 shadow-sm dark:bg-slate-800 dark:text-slate-200">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Protected route</p>
                        <p class="mt-1 text-lg font-semibold">/dev/api-docs</p>
                    </div>
                </div>
            <section class="rounded-3xl border border-slate-200/80 bg-white/95 p-6 shadow-sm dark:border-slate-700/70 dark:bg-slate-900/95">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Global Authentication</h3>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Enter your Bearer token for API testing:</p>
                <input type="text" id="global-token" class="mt-2 w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white" placeholder="Bearer eyJ0eXAiOiJKV1QiLCJh...">
            </section>
                <article class="space-y-6 rounded-3xl border border-slate-200/80 bg-white/95 p-6 shadow-sm dark:border-slate-700/70 dark:bg-slate-900/95">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 text-slate-900 dark:text-white">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-500 text-white ring-1 ring-blue-500/20"><i class="fa-solid fa-shield-halved"></i></span>
                            <div>
                                <h3 class="text-xl font-semibold">Authentication</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Gunakan Laravel Sanctum untuk semua endpoint ber-auth.</p>
                            </div>
                        </div>

                        <div class="rounded-3xl bg-slate-50 p-5 dark:bg-slate-950/80">
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">Token flow</p>
                            <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">Login melalui <code class="rounded-md bg-slate-900/5 px-2 py-0.5 text-sm text-slate-900 dark:bg-slate-700/80 dark:text-slate-100">POST /api/login</code> untuk menerima token, lalu sertakan token di header:</p>
                            <pre class="mt-4 overflow-x-auto rounded-3xl bg-slate-900/95 p-4 text-xs text-slate-100"><code>Authorization: Bearer {token}</code></pre>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="rounded-3xl border border-slate-200/80 bg-slate-50 p-4 dark:border-slate-700/70 dark:bg-slate-950/80">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Request Example</p>
                                <div class="mt-3 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                                    <p><span class="font-semibold">POST</span> /api/login</p>
                                    <pre class="rounded-2xl bg-slate-900/95 p-3 text-xs text-slate-100"><code>{
  "nik": "superadmin@example.com",
  "password": "password"
}</code></pre>
                                </div>
                            </div>

                            <div class="rounded-3xl border border-slate-200/80 bg-slate-50 p-4 dark:border-slate-700/70 dark:bg-slate-950/80">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Response Example</p>
                                <pre class="mt-3 overflow-x-auto rounded-2xl bg-slate-900/95 p-3 text-xs text-slate-100"><code>{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "token": "eyJ0eXAiOiJKV1QiLCJh..."
  }
}</code></pre>
                            </div>
                        </div>
                    </div>
                </article>

                <aside class="space-y-6 rounded-3xl border border-slate-200/80 bg-white/95 p-6 shadow-sm dark:border-slate-700/70 dark:bg-slate-900/95">
                    <div class="space-y-3">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Security</p>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Akses Terbatas</h3>
                        <p class="text-sm leading-6 text-slate-600 dark:text-slate-300">Hanya Superadmin yang dapat mengakses halaman ini. Pengguna lain akan menerima 403 Forbidden.</p>
                    </div>

                    <div class="rounded-3xl bg-slate-50 p-4 dark:bg-slate-950/80">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Custom Gate</p>
                        <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">Gate <code class="rounded-md bg-slate-900/5 px-2 py-0.5 text-xs dark:bg-slate-800">view-api-docs</code> memeriksa role_id == 1.</p>
                    </div>

                    <div class="rounded-3xl bg-slate-50 p-4 dark:bg-slate-950/80">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Route</p>
                        <p class="mt-2 text-sm text-slate-700 dark:text-slate-200"><code class="rounded-md bg-slate-900/5 px-2 py-0.5 dark:bg-slate-800">GET /dev/api-docs</code></p>
                    </div>
                </aside>
            </section>

            <section class="space-y-4">
                @foreach($groups as $group)
                    <div class="rounded-3xl border border-slate-200/80 bg-white/95 p-6 shadow-sm dark:border-slate-700/70 dark:bg-slate-900/95">
                        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h3 class="text-xl font-semibold text-slate-900 dark:text-white">{{ $group['title'] }}</h3>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $group['description'] }}</p>
                            </div>
                            <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-blue-700 dark:bg-blue-500/10 dark:text-blue-300">Module</span>
                        </div>

                        <div class="space-y-4">
                            @foreach($group['items'] as $item)
                                <div class="rounded-3xl border border-slate-200/80 bg-slate-50 p-5 dark:border-slate-700/70 dark:bg-slate-950/80">
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                        <div>
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="rounded-full bg-slate-900 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-white dark:bg-slate-200 dark:text-slate-900">{{ $item['method'] }}</span>
                                                <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $item['uri'] }}</span>
                                            </div>
                                            <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $item['description'] }}</p>
                                        </div>
                                        <div class="text-sm text-slate-500 dark:text-slate-400">
                                            Headers
                                            <div class="mt-2 rounded-2xl bg-slate-900/95 p-3 text-xs text-slate-100">
                                                @foreach($item['headers'] as $key => $value)
                                                    <p><span class="font-semibold">{{ $key }}:</span> {{ $value }}</p>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    @if(isset($item['body']))
                                    <div class="mt-4">
                                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Request Body</p>
                                        <pre class="mt-2 overflow-x-auto rounded-2xl bg-slate-900/95 p-4 text-xs text-slate-100"><code>{{ json_encode($item['body'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                    </div>
                                    @endif

                                    <div class="mt-4">
                                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Example Response</p>
                                        <pre class="mt-2 overflow-x-auto rounded-2xl bg-slate-900/95 p-4 text-xs text-slate-100"><code>{{ json_encode($item['response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                    </div>

                                    <div class="mt-4">
                                        <button type="button" class="try-it-out-btn rounded-lg bg-blue-500 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-600" data-method="{{ $item['method'] }}" data-uri="{{ $item['uri'] }}" data-has-body="{{ isset($item['body']) ? 'true' : 'false' }}" data-default-body="{{ isset($item['default_body']) ? $item['default_body'] : '' }}">
                                            Try It Out
                                        </button>
                                    </div>

                                    <div class="try-it-form mt-4 hidden rounded-3xl border border-slate-200/80 bg-slate-100 p-4 dark:border-slate-700/70 dark:bg-slate-950/80">
                                        @php
                                            $pathParams = [];
                                            preg_match_all('/\{([^}]+)\}/', $item['uri'], $matches);
                                            if (!empty($matches[1])) {
                                                $pathParams = $matches[1];
                                            }
                                        @endphp
                                        @if(count($pathParams) > 0)
                                            <div class="mb-4">
                                                <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">Path Parameters</p>
                                                @foreach($pathParams as $param)
                                                    <div class="mt-2">
                                                        <label class="block text-xs text-slate-600 dark:text-slate-300">{{ $param }}</label>
                                                        <input type="text" class="path-param w-full rounded border border-slate-300 bg-white px-2 py-1 text-sm dark:border-slate-600 dark:bg-slate-700 dark:text-white" data-param="{{ $param }}" placeholder="Enter {{ $param }}">
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                        @if(in_array($item['method'], ['POST', 'PUT']) && isset($item['body']))
                                            <div class="mb-4">
                                                <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">Request Body (JSON)</p>
                                                <textarea class="request-body w-full rounded border border-slate-300 bg-white px-2 py-1 text-sm font-mono dark:border-slate-600 dark:bg-slate-700 dark:text-white" rows="6">{{ isset($item['default_body']) ? $item['default_body'] : json_encode($item['body'], JSON_PRETTY_PRINT) }}</textarea>
                                            </div>
                                        @endif
                                        <button type="button" class="send-request-btn rounded-lg bg-green-500 px-4 py-2 text-sm font-semibold text-white hover:bg-green-600" data-method="{{ $item['method'] }}" data-uri="{{ $item['uri'] }}" data-has-body="{{ isset($item['body']) ? 'true' : 'false' }}">
                                            Send Request
                                        </button>
                                        <div class="response-area mt-4 hidden">
                                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">Response</p>
                                            <div class="mt-2 rounded-2xl bg-slate-900/95 p-4 text-xs text-slate-100">
                                                <div class="response-status mb-2"></div>
                                                <div class="response-headers mb-2"></div>
                                                <pre class="response-body"></pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </section>

            <section class="rounded-3xl border border-slate-200/80 bg-white/95 p-6 shadow-sm dark:border-slate-700/70 dark:bg-slate-900/95">
                <h3 class="text-xl font-semibold text-slate-900 dark:text-white">Rekomendasi Backend</h3>
                <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">
                    {{ $recommendation }}
                </p>
                <ul class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                    <li class="flex items-start gap-2"><span class="mt-1 inline-flex h-2.5 w-2.5 rounded-full bg-blue-500"></span> Dokumentasi manual di Blade memberikan kontrol penuh atas teks, struktur, dan contoh payload tanpa mengubah API controller.</li>
                    <li class="flex items-start gap-2"><span class="mt-1 inline-flex h-2.5 w-2.5 rounded-full bg-blue-500"></span> Paket seperti <code class="rounded-md bg-slate-900/5 px-2 py-0.5 text-xs dark:bg-slate-800">knuckleswtf/scribe</code> cocok bila API sudah standar dan Anda ingin dokumentasi otomatis dari komentar dan response schema.</li>
                    <li class="flex items-start gap-2"><span class="mt-1 inline-flex h-2.5 w-2.5 rounded-full bg-blue-500"></span> Untuk sekarang, gunakan halaman ini sebagai referensi internal dan update endpoint manual bila controller berubah.</li>
                </ul>
            </section>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Try It Out button click
            document.querySelectorAll('.try-it-out-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const form = this.nextElementSibling;
                    form.classList.toggle('hidden');
                });
            });

            // Send Request button click
            document.querySelectorAll('.send-request-btn').forEach(btn => {
                btn.addEventListener('click', async function() {
                    const method = this.dataset.method;
                    let uri = this.dataset.uri;
                    const hasBody = this.dataset.hasBody === 'true';

                    // Handle path params
                    const pathInputs = this.closest('.try-it-form').querySelectorAll('.path-param');
                    pathInputs.forEach(input => {
                        const param = input.dataset.param;
                        const value = input.value.trim();
                        if (value) {
                            uri = uri.replace(`{${param}}`, value);
                        }
                    });

                    // Get token
                    const token = document.getElementById('global-token').value.trim();

                    // Build request options
                    const options = {
                        method: method,
                        headers: {
                            'Accept': 'application/json',
                        }
                    };

                    if (token) {
                        options.headers['Authorization'] = `Bearer ${token}`;
                    }

                    if (hasBody && (method === 'POST' || method === 'PUT')) {
                        const bodyTextarea = this.closest('.try-it-form').querySelector('.request-body');
                        if (bodyTextarea) {
                            options.headers['Content-Type'] = 'application/json';
                            try {
                                options.body = JSON.stringify(JSON.parse(bodyTextarea.value));
                            } catch (e) {
                                alert('Invalid JSON in request body');
                                return;
                            }
                        }
                    }

                    // Confirmation for DELETE
                    if (method === 'DELETE') {
                        if (!confirm('Are you sure you want to delete this resource?')) {
                            return;
                        }
                    }

                    // Show loading
                    const responseArea = this.closest('.try-it-form').querySelector('.response-area');
                    responseArea.classList.remove('hidden');
                    const statusDiv = responseArea.querySelector('.response-status');
                    const headersDiv = responseArea.querySelector('.response-headers');
                    const bodyPre = responseArea.querySelector('.response-body');
                    statusDiv.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Loading...';
                    headersDiv.innerHTML = '';
                    bodyPre.textContent = '';

                    try {
                        const response = await fetch(uri, options);
                        const responseText = await response.text();
                        let responseJson = null;
                        try {
                            responseJson = JSON.parse(responseText);
                        } catch (e) {
                            // Not JSON
                        }

                        // Status
                        let statusClass = 'text-green-400';
                        if (response.status >= 400 && response.status < 500) {
                            statusClass = 'text-yellow-400';
                        } else if (response.status >= 500) {
                            statusClass = 'text-red-400';
                        }
                        statusDiv.innerHTML = `<span class="${statusClass}">Status: ${response.status} ${response.statusText}</span>`;

                        // Headers
                        let headersHtml = '<strong>Headers:</strong><br>';
                        response.headers.forEach((value, key) => {
                            headersHtml += `${key}: ${value}<br>`;
                        });
                        headersDiv.innerHTML = headersHtml;

                        // Body
                        if (responseJson) {
                            bodyPre.textContent = JSON.stringify(responseJson, null, 2);
                        } else {
                            bodyPre.textContent = responseText;
                        }
                    } catch (error) {
                        statusDiv.innerHTML = '<span class="text-red-400">Error: ' + error.message + '</span>';
                    }
                });
            });
        });
    </script>
@endsection
