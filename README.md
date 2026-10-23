# Mini CRM de Contatos

 API em Laravel para resolver o desafio de gestão e qualificação automática de contatos. 

A ideia principal aqui foi construir uma solução funcional e organizada, que calcula automaticamente uma pontuação de relevância (Score) para cada novo lead ou atualização cadastral.

## Como estruturei o cálculo do Score

A pontuação varia de 0 a 100 pontos e é calculada combinando estas regras:

- +20 pontos: E-mail corporativo (domínios privados, ignorando provedores públicos como Gmail, Hotmail, etc.).
- +20 pontos: Domínio de e-mail brasileiro (.br).
- +20 pontos: Telefone com DDD de São Paulo (11).
- +20 pontos: Nome completo (validação de nome e sobrenome).

## Arquitetura do Projeto

Para garantir que a regra de negócio do Score ficasse totalmente independente de banco de dados ou de decisões de framework, apliquei conceitos de Clean Architecture e DDD:

- Value Objects: Criei classes específicas para encapsular a validação de Email e Phone.
- Domain Service: A lógica do cálculo do Score fica isolada na classe `ScoreCalculator`, facilitando testes unitários sem mockar banco.
- Use Cases: Centralizam as ações de criar (`CreateContactUseCase`) e atualizar (`UpdateContactUseCase`) contatos.
- Repository Pattern: O controller conversa apenas com a interface `ContactRepositoryInterface`, mantendo o Eloquent desacoplado na camada de infraestrutura.
- TDD: Escrevi testes unitários para a regra do score e testes de integração cobrindo todas as rotas da API.

## Como rodar o projeto na sua máquina

Pré-requisitos:
- PHP >= 8.2
- Composer
- Extensão pdo_sqlite habilitada no php.ini

Passo a passo:

1. Clonar o repositório:
git clone https://github.com/Matheus-Munhos/laravel-mini-crm-contatos.git
cd laravel-mini-crm-contatos

2. Instalar as dependências:
composer install

3. Configurar o ambiente:
cp .env.example .env
php artisan key:generate

4. Executar as migrations:
php artisan migrate

5. Subir o servidor local:
php artisan serve

## Como rodar os testes

Para validar os testes unitários e de integração:

php artisan test

## Rotas disponíveis (/api/contacts)

| Método | Rota | O que faz |
| :--- | :--- | :--- |
| GET | /api/contacts | Retorna a lista de contatos |
| POST | /api/contacts | Cria o contato e calcula o score inicial |
| GET | /api/contacts/{id} | Exibe um contato específico |
| PUT | /api/contacts/{id} | Atualiza o contato e recalcula o score |
| DELETE | /api/contacts/{id} | Remove o contato |