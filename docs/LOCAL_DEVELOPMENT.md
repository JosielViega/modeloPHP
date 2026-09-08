# Desenvolvimento local

> Cada projeto deve utilizar uma porta local própria.

## Configuração automática

Depois de `composer install`, execute:

```bash
composer setup
```

Se `.env` não existir, ele será copiado de `.env.example`. Se existir, seu conteúdo será preservado. Uma porta inválida ou ocupada faz o setup procurar sequencialmente uma porta livre entre 8010 e 8999, sem encerrar processos. O comando altera apenas `APP_PORT` e, quando ela representa localhost, `APP_URL`; URLs não locais são preservadas.

## Configuração manual

Copie `.env.example` para `.env` e escolha a porta:

```env
APP_URL=http://localhost:8010
APP_PORT=8010
```

A porta `8010` é somente o início sugerido da busca e pode estar ocupada em outra máquina ou no futuro.

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
