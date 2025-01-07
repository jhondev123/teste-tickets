# URLS
- SWAGGER <https://app.swaggerhub.com/apis-docs/JHONATTANCURTARELLI1/tickets-api/1.0.0>
- APP em produção <https://teste-tickets-front.vercel.app/>
- A API em si está na URL <https://teste-tickets-api.projetos.jhonattancurtarelli.com.br/api/v1/>
Essa URL não vai conter nada, os endpoints tente /tickets ou /employees

# INFRA

## Tecnologias
- Docker && Docker Compose
- Nginx

## Porque da Escolha?
- Bem como eu desenvolvi o projeto no formato de uma aplicação WEB, caso precisassem instalar localmente
  para testar o projeto não iria ser fácil
- Dessa forma usei meus conhecimentos básicos de infra para criar uma forma de instalação mais fácil

## Como Funciona?
- No Arquivo docker-compose.yml ele contém toda configuração para subir o projeto
- Eu deixei dentro de cada repositório em docker/dockerfile ele é um arquivo que contem a criação da imagem
  dos containers usados
- para fazer o gerenciamento das portas de acessos dos apps usei NGINX, e de forma bem rudimentar
  criei dois containers da API e fiz um load-balancer neles. Lógicamente em uma aplicação de produção de alta
  escalabilidade teria que ser usado kubernetes ou algo do tipo.
- O NGINX eu simplesmente configuro aonde vai ser o load-balancer, aonde vai ser as APIS e aonde o front vai
  responder
- Na hora que os containers sobem eu rodo um arquivo chamado entrypoint.sh, ele executa uma sequência de comandos
  que precisam ser realizados quando o app já está no container, como rodar as migrations por exemplo.

## Como Rodar?
- Para rodar vai precisar ter em sua máquina o docker e o docker-compose
  Caso não tenha e esteja usando windows, recomendo instalar o WSL2 primeiro e depois o docker, pois a
  compatibilidade dos dois é muito boa
- Depois de instalado é só clonar o repositório e rodar o comando `docker-compose up --build` ele vai
  baixar as imagens das tecs usadas e subir os containers de forma automática
- Depois de subir tudo é só jogar no navegador <http://localhost> e o sistema vai estar rodando

## Sistema em Produção?
- Sim o sistema está funcionando em produção também, eu possuo uma VPS com CAPROVER instalado nela, assim subi
  o backend e o banco de dados nela. depois subi o frontend na vercel e eles se comunicam normalmente.
  Subi o frontend na vercel pois lá a compatibilidade com NEXTJS é natural e o deploy é bem fácil de ser feito.

## Postman
- Além da documentação via SWAGGER, vou disponibilizar um arquivo de collection do postman para facilitar
  tanto o arquivo da collection quando o arquivo do enviroment que contém a URL da API configurada

## Git
- Para trabalhar com 2 repositórios separados ao mesmo tempo e juntar os dois em um terceiro eu usei
  sub módulos do git, assim eu consigo trabalhar com os dois repositórios separados e juntar eles em um terceiro
