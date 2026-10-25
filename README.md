# Mini CRM de Contatos

API em Laravel desenvolvida para gestão e qualificação de contatos (leads). O foco principal do projeto foi construir uma solução desacoplada, extensível e totalmente coberta por testes, calculando o Score de engajamento de forma assíncrona para cada contato cadastrado ou atualizado.

---

## Como estruturei o cálculo do Score

O contato é registrado inicialmente com score 0 e status "pending"[cite: 7]. Quando o cálculo assíncrono é disparado[cite: 7], a pontuação acumula até 60 pontos a partir das seguintes condições[cite: 7]:

- +20 pontos: E-mail corporativo (domínios privados, ignorando provedores públicos como Gmail, Hotmail, etc.)[cite: 7].
- +10 pontos: Domínio de e-mail brasileiro (.br)[cite: 7].
- +20 pontos: Telefone com código de área (DDD) do estado de São Paulo (11 a 19).
- +10 pontos: Telefone com código de área (DDD) de outros estados.
- +10 pontos: Nome completo (validação de nome e sobrenome)[cite: 7].

---

## Arquitetura do Projeto

Para garantir que a regra de negócio do Score ficasse totalmente independente de banco de dados ou de decisões de framework, apliquei conceitos de Clean Architecture e DDD[cite: 8]:

- Strategy Pattern: Isolei as regras de pontuação em classes dedicadas (CorporateEmailRule, BrazilianDomainRule, SpPhoneRule, FullNameRule)[cite: 7, 9], orquestradas pelo ScoreCalculator[cite: 14]. Novas regras podem ser adicionadas sem alterar as já existentes.
- Value Objects: Criei classes específicas para encapsular a validação e integridade de Email e Phone[cite: 8].
- Domain Service: A lógica do cálculo do Score fica isolada na classe ScoreCalculator, facilitando testes unitários sem mockar banco.
- Use Cases: Centralizam as ações de negócio de criar (CreateContactUseCase) e atualizar (UpdateContactUseCase) contatos[cite: 14].
- Repository Pattern: O controller conversa apenas com a interface ContactRepositoryInterface, mantendo o Eloquent desacoplado na camada de infraestrutura[cite: 8, 14].
- Observers: Utilizei o ContactObserver para garantir a sanitização automática do telefone no evento de persistência (saving)[cite: 11].
- Queues & WebSockets: O processamento do score é despachado via job (ProcessContactScore) em fila Redis com simulação de carga, atualizando status para "processing" e depois "active" com timestamp (processed_at)[cite: 1]. Ao concluir, dispara o evento ContactScoreProcessed via Laravel Reverb no canal individual do contato (contacts.{id})[cite: 1, 8].
- Listeners & Logs: O listener LogContactScoreProcessed registra a alteração do contato em storage/logs/contact.log[cite: 1, 8].
- TDD: Escrevi testes unitários para a regra do score e testes de integração cobrindo todas as rotas da API, enfileiramento do job e soft deletes[cite: 6, 7, 9].

---

## Como rodar o projeto na sua máquina

Pré-requisitos:
- Docker Desktop (caso use Laravel Sail) OU PHP >= 8.2 com Composer e extensão pdo_sqlite habilitada.

Opção 1: Via Docker / Laravel Sail (Recomendado)

1. Clonar o repositório:
git clone https://github.com/Matheus-Munhos/laravel-mini-crm-contatos.git
cd laravel-mini-crm-contatos

2. Instalar as dependências:
composer install

3. Configurar as variáveis:
cp .env.example .env

4. Subir os containers:
./vendor/bin/sail up -d

5. Gerar chave e rodar migrations:
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate


Opção 2: Rodando Localmente (Sem Docker)

1. Clonar o repositório:
git clone https://github.com/Matheus-Munhos/laravel-mini-crm-contatos.git
cd laravel-mini-crm-contatos

2. Instalar as dependências:
composer install

3. Configurar ambiente e chave:
cp .env.example .env
php artisan key:generate

4. Executar as migrations:
php artisan migrate

5. Subir o servidor:
php artisan serve

---

## Filas e WebSockets

Para processar os jobs da fila (Redis):
- Sail: ./vendor/bin/sail artisan queue:work
- Local: php artisan queue:work

Para iniciar o servidor de WebSockets do Reverb:
- Sail: ./vendor/bin/sail artisan reverb:start
- Local: php artisan reverb:start

---

## Exemplo de Escuta do WebSocket (HTML / JavaScript)

Exemplo de cliente para escutar as atualizações de score em tempo real disparadas pelo Reverb no canal individual do contato:

```html
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>CRM Live Feed</title>
    <script src="https://cdn.jsdelivr.net/npm/pusher-js@8.4.0-rc2/dist/web/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
</head>
<body>
    <h2>Score Atualizado em Tempo Real:</h2>
    <ul id="contacts-list"></ul>

    <script>
        const echo = new Echo({
            broadcaster: 'reverb',
            key: 'seu_app_key_reverb',
            wsHost: window.location.hostname || '127.0.0.1',
            wsPort: 8080,
            forceTLS: false,
            enabledTransports: ['ws', 'wss'],
        });

        const contactId = 1; // ID do contato monitorado

        echo.channel(`contacts.${contactId}`)
            .listen('.score.processed', (e) => {
                const list = document.getElementById('contacts-list');
                const item = document.createElement('li');
                item.textContent = e.contact.name + ' (' + e.contact.email + ') - Novo Score: ' + e.contact.score + ' | Status: ' + e.contact.status;
                list.appendChild(item);
            });
    </script>
</body>
</html>
```

---

## Como rodar os testes

Para executar todos os testes da aplicação:

php artisan test

(Caso esteja usando Sail: ./vendor/bin/sail test)

Para rodar os testes individualmente por método:

- Apenas criação com score zero e status pending:
php artisan test --filter=test_pode_criar_um_contato_via_api_com_score_zero_e_status_pending

- Apenas atualização com recálculo de score:
php artisan test --filter=test_pode_atualizar_um_contato_e_recalcular_score

- Apenas exclusão (soft delete):
php artisan test --filter=test_pode_deletar_um_contato

- Apenas enfileiramento do job de score:
php artisan test --filter=test_pode_enfileirar_processamento_de_score

---

## Rotas disponíveis (/api/contacts)

- GET /api/contacts -> Retorna a lista de contatos (com paginação)[cite: 7]
- POST /api/contacts -> Cria o contato (inicia com score 0 e status pending)[cite: 7]
- GET /api/contacts/{id} -> Exibe um contato específico[cite: 7]
- PUT /api/contacts/{id} -> Atualiza o contato e recalcula os dados[cite: 7]
- DELETE /api/contacts/{id} -> Remove o contato (soft delete)[cite: 6, 7]
- POST /api/contacts/{id}/process-score -> Enfileira o cálculo assíncrono do score do contato (retorna HTTP 202)[cite: 7]