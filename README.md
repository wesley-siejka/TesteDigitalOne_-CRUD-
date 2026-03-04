# Teste Digital One - CRUD de Usuários (Laravel)

Este projeto é uma aplicação full stack dividida em **API (backend)** e **interface web (frontend)**.

O sistema permite gerenciar usuários **Pessoa Física (PF)** e **Pessoa Jurídica (PJ)** com controle de permissões entre **administrador** e **usuário simples**.

---

# Funcionalidades

## Autenticação
- Login com **Laravel Sanctum**
- Controle de sessão no frontend
- Troca de senha pelo próprio usuário
- Reset de senha por administrador

## Usuários
- CRUD completo de usuários
- Suporte para:
  - Pessoa Física (CPF)
  - Pessoa Jurídica (CNPJ)
- Soft Delete
- Validação de permissões com **Policies**

## Endereço
- Consulta automática de endereço via **ViaCEP**
- Preenchimento automático de:
  - Logradouro
  - Bairro
  - Cidade
  - Estado
- Fallback caso o serviço do ViaCEP esteja indisponível

## Validação de documentos
- CPF e CNPJ passam por validação antes da criação ou edição de usuários.
- A validação utiliza:
  - **algoritmo local de validação**
  - **consulta à API ReceitaWS** para verificar CNPJ válido.

---

# Tecnologias Utilizadas

## Backend
- PHP
- Laravel
- Laravel Sanctum
- MySQL
- Scribe (documentação OpenAPI)

## Frontend
- Blade
- Bootstrap
- JavaScript

---

# APIs externas utilizadas

## ViaCEP
Utilizada para buscar automaticamente o endereço a partir do CEP informado.

## ReceitaWS
Utilizada para validação de **CNPJ**.

---

# Estrutura do Projeto

O frontend consome a API via HTTP.

---

# Documentação da API

A documentação da API está disponível em: /api/docs

Gerada automaticamente utilizando **Scribe (OpenAPI / Swagger)**.

---

# Como Rodar o Projeto

## 1 - Clonar o repositório
https://github.com/wesley-siejka/TesteDigitalOne_-CRUD-.git

---

# Rodar o Backend (API)

Entrar na pasta: cd backend.
Instalar dependências: composer install.
Copiar arquivo de ambiente: copy .env.example .env
Configurar o banco de dados no arquivo `.env`
Rodar migrations: php artisan migrate
Rodar as seeders: php artisan seed
Iniciar servidor: php artisan serve --port=8000 (preferencia na porta: 8000)

---

# Rodar o Frontend

Entrar na pasta: cd frontend
Instalar dependências: composer install
Copiar arquivo de ambiente: copy .env.example .env
Iniciar servidor: php artisan serve --port=8001

---

# Fluxo da Aplicação

1. Usuário acessa o frontend
2. Realiza login
3. O frontend consome a API autenticada
4. A API valida permissões via **Policies**

---

# Perfis de Usuário

## Administrador
- Criar usuários
- Editar qualquer usuário
- Excluir usuários
- Resetar senha de outros usuários

## Usuário simples
- Visualizar apenas o próprio perfil
- Editar seus próprios dados
- Alterar sua própria senha
