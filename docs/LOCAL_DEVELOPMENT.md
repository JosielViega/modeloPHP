# Desenvolvimento local

> Cada projeto deve utilizar uma porta local própria.

## Configuração

Copie `.env.example` para `.env` e escolha a porta:

```env
APP_URL=http://localhost:8010
APP_PORT=8010
```

A porta `8010` foi confirmada livre quando este template foi criado, mas pode estar ocupada em outra máquina ou no futuro.

## Verificar disponibilidade

No Windows PowerShell:

```powershell
Get-NetTCPConnection -State Listen | Where-Object LocalPort -eq 8010
```

No Linux/macOS, uma opção comum é:

```bash
lsof -iTCP:8010 -sTCP:LISTEN
```

Saída indicando um listener significa que a porta já pertence a outro processo. Não encerre esse processo: escolha uma nova porta livre para este projeto e atualize `APP_PORT` e `APP_URL` juntos.

## Iniciar

```bash
composer serve
```

O comando verifica novamente a disponibilidade antes de executar o servidor PHP em `127.0.0.1`. Se ocorrer uma corrida e outro processo ocupar a porta, o PHP falhará sem o template tentar encerrá-lo.

## Vários projetos

Mantenha uma porta diferente em cada `.env`, que não é versionado. O `.env.example` serve apenas como ponto inicial para clones novos; adapte-o ao ambiente. Apache permanece a referência para produção, e sua configuração de virtual host pode dispensar `APP_PORT`.
