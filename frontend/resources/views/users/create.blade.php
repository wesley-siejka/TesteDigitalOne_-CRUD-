@extends('layouts.app')

@section('title', 'Novo Usuário')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Novo Usuário</h3>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Confira os campos:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/users">
        @csrf

        {{-- DADOS BASE --}}
        <div class="card mb-3">
            <div class="card-header">Dados do usuário</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Senha</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tipo</label>
                        <select class="form-select @error('tipo') is-invalid @enderror" name="tipo" id="tipo">
                            <option value="pf" @selected(old('tipo', 'pf') === 'pf')>Pessoa Física</option>
                            <option value="pj" @selected(old('tipo') === 'pj')>Pessoa Jurídica</option>
                        </select>
                        @error('tipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nível</label>
                        <select class="form-select @error('nivel') is-invalid @enderror" name="nivel">
                            <option value="simples" @selected(old('nivel', 'simples') === 'simples')>Simples</option>
                            <option value="admin" @selected(old('nivel') === 'admin')>Admin</option>
                        </select>
                        @error('nivel')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status">
                            <option value="ativo" @selected(old('status', 'ativo') === 'ativo')>Ativo</option>
                            <option value="inativo" @selected(old('status') === 'inativo')>Inativo</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- PF --}}
        <div class="card mb-3" id="bloco-pf">
            <div class="card-header">Dados Pessoa Física</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nome</label>
                        <input class="form-control @error('nome') is-invalid @enderror" name="nome"
                            value="{{ old('nome') }}">
                        @error('nome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">CPF</label>
                        <input class="form-control @error('cpf') is-invalid @enderror" name="cpf"
                            value="{{ old('cpf') }}">
                        @error('cpf')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nascimento</label>
                        <input type="date" class="form-control @error('nascimento') is-invalid @enderror"
                            name="nascimento" value="{{ old('nascimento') }}">
                        @error('nascimento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- PJ --}}
        <div class="card mb-3" id="bloco-pj">
            <div class="card-header">Dados Pessoa Jurídica</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Razão Social</label>
                        <input class="form-control @error('razao_social') is-invalid @enderror" name="razao_social"
                            value="{{ old('razao_social') }}">
                        @error('razao_social')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nome Fantasia</label>
                        <input class="form-control @error('nome_fantasia') is-invalid @enderror" name="nome_fantasia"
                            value="{{ old('nome_fantasia') }}">
                        @error('nome_fantasia')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">CNPJ</label>
                        <input class="form-control @error('cnpj') is-invalid @enderror" name="cnpj"
                            value="{{ old('cnpj') }}">
                        @error('cnpj')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ENDEREÇO --}}
        <div class="card mb-3">
            <div class="card-header">Endereço</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">CEP</label>
                        <input class="form-control @error('cep') is-invalid @enderror" name="cep"
                            value="{{ old('cep') }}" placeholder="Somente números">
                        @error('cep')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Número</label>
                        <input class="form-control @error('numero') is-invalid @enderror" name="numero"
                            value="{{ old('numero') }}">
                        @error('numero')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Complemento</label>
                        <input class="form-control @error('complemento') is-invalid @enderror" name="complemento"
                            value="{{ old('complemento') }}">
                        @error('complemento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Logradouro</label>
                            <input class="form-control" id="logradouro" name="logradouro"
                                value="{{ old('logradouro') }}" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bairro</label>
                            <input class="form-control" id="bairro" name="bairro" value="{{ old('bairro') }}"
                                readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cidade</label>
                            <input class="form-control" id="cidade" name="cidade" value="{{ old('cidade') }}"
                                readonly>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">Estado</label>
                            <input class="form-control" id="estado" name="estado" value="{{ old('estado') }}"
                                readonly>
                        </div>

                        <div class="col-md-4 mb-3 d-flex align-items-end">
                            <div id="cep-status" class="small text-muted"></div>
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-success">Salvar</button>
            <a href="/users" class="btn btn-secondary">Cancelar</a>
        </div>

    </form>

    <script>
        (function() {
            // ===== Helpers =====
            const onlyDigits = (v) => (v || '').replace(/\D/g, '');

            function maskCEP(v) {
                v = onlyDigits(v).slice(0, 8);
                // 12345-678
                return v.length > 5 ? v.slice(0, 5) + '-' + v.slice(5) : v;
            }

            function maskCPF(v) {
                v = onlyDigits(v).slice(0, 11);
                // 123.456.789-01
                if (v.length <= 3) return v;
                if (v.length <= 6) return v.slice(0, 3) + '.' + v.slice(3);
                if (v.length <= 9) return v.slice(0, 3) + '.' + v.slice(3, 6) + '.' + v.slice(6);
                return v.slice(0, 3) + '.' + v.slice(3, 6) + '.' + v.slice(6, 9) + '-' + v.slice(9);
            }

            function maskCNPJ(v) {
                v = onlyDigits(v).slice(0, 14);
                // 12.345.678/0001-90
                if (v.length <= 2) return v;
                if (v.length <= 5) return v.slice(0, 2) + '.' + v.slice(2);
                if (v.length <= 8) return v.slice(0, 2) + '.' + v.slice(2, 5) + '.' + v.slice(5);
                if (v.length <= 12) return v.slice(0, 2) + '.' + v.slice(2, 5) + '.' + v.slice(5, 8) + '/' + v.slice(8);
                return v.slice(0, 2) + '.' + v.slice(2, 5) + '.' + v.slice(5, 8) + '/' + v.slice(8, 12) + '-' + v.slice(
                    12);
            }

            function applyMask(input, masker) {
                if (!input) return;
                input.addEventListener('input', () => {
                    const start = input.selectionStart;
                    const before = input.value;
                    input.value = masker(input.value);

                    // tentativa simples de manter cursor (sem paranoia)
                    const delta = input.value.length - before.length;
                    const pos = (start ?? input.value.length) + delta;
                    input.setSelectionRange(pos, pos);
                });
            }

            // ===== PF/PJ toggle =====
            const tipo = document.getElementById('tipo');
            const pf = document.getElementById('bloco-pf');
            const pj = document.getElementById('bloco-pj');

            function toggleTipo() {
                const v = tipo.value;
                pf.style.display = (v === 'pf') ? '' : 'none';
                pj.style.display = (v === 'pj') ? '' : 'none';
            }
            tipo.addEventListener('change', toggleTipo);
            toggleTipo();

            // ===== Inputs =====
            const cepInput = document.querySelector('input[name="cep"]');
            const cpfInput = document.querySelector('input[name="cpf"]');
            const cnpjInput = document.querySelector('input[name="cnpj"]');

            // aplica máscaras
            applyMask(cepInput, maskCEP);
            applyMask(cpfInput, maskCPF);
            applyMask(cnpjInput, maskCNPJ);

            // ===== CEP auto lookup (8 dígitos) =====
            const statusEl = document.getElementById('cep-status');
            const logradouro = document.getElementById('logradouro');
            const bairro = document.getElementById('bairro');
            const cidade = document.getElementById('cidade');
            const estado = document.getElementById('estado');

            let lastCep = null;
            let timer = null;

            function setStatus(msg) {
                if (statusEl) statusEl.textContent = msg || '';
            }

            function clearAddress() {
                if (!logradouro) return;
                logradouro.value = '';
                bairro.value = '';
                cidade.value = '';
                estado.value = '';
            }

            async function fetchCep(cep) {
                setStatus('Consultando CEP...');
                try {
                    const res = await fetch(`/cep/${cep}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    const data = await res.json().catch(() => ({}));

                    if (!res.ok) {
                        clearAddress();
                        setStatus(data.message || 'Erro ao consultar CEP');
                        return;
                    }

                    if (data.erro === true || (!data.logradouro && !data.bairro)) {
                        clearAddress();
                        setStatus('CEP não encontrado');
                        return;
                    }

                    logradouro.value = data.logradouro ?? '';
                    bairro.value = data.bairro ?? '';
                    cidade.value = data.cidade ?? data.localidade ?? '';
                    estado.value = data.estado ?? data.uf ?? '';

                    setStatus('CEP encontrado ✅');
                } catch (e) {
                    clearAddress();
                    setStatus('Falha de conexão');
                }
            }

            function onCepChange() {
                const digits = onlyDigits(cepInput.value);
                if (digits.length !== 8) {
                    setStatus('');
                    clearAddress();
                    lastCep = null;
                    return;
                }

                if (digits === lastCep) return;
                lastCep = digits;
                fetchCep(digits);
            }

            if (cepInput) {
                cepInput.addEventListener('input', () => {
                    clearTimeout(timer);
                    timer = setTimeout(onCepChange, 350);
                });

                // se vier preenchido via old()
                setTimeout(onCepChange, 0);
            }
        })();
    </script>
@endsection
