# Oficina2.0 :wrench: :wrench:
 Sistema para oficina mecânica (cadastro de orçamento)
 
 Autor: Rafael Buçard 


Crud OOP com sistema de busca e filtro  feito em PHP7 respeitando a PSR-4.  
 
 **(O Objetivo do Projeto é demonstrar habilidades com a linguagem PHP , integração com banco de dados MySQL, Bootstrap e gerenciador de dependências Composer, para criação de um crud orientado a objeto simples, rápido e visualmente adequado e responsivo, além de criar uma ferramenta usual para o dia dia de uma oficina mecânica.)**
 
### Ferramentas:
* PHP: 8.3
* PostgreSQL 16
* Composer
* BootstrapCDN
* Docker + Docker Compose

### Executando (exclusivamente via Docker):

O projeto roda **somente** com Docker — não é necessário instalar PHP ou PostgreSQL na máquina.

```bash
docker compose up --build
```

Depois acesse: **http://localhost:8000**

A tabela `repair` é criada automaticamente na primeira subida (via `docker/init.sql`).

Para parar e remover os containers (mantendo os dados):

```bash
docker compose down
```

Para remover também o banco de dados (volume):

```bash
docker compose down -v
```
 
   
 ### BootstrapCDN:
 
LINK: https://getbootstrap.com.br/docs/4.1/getting-started/introduction/  

   
 ### Base de dados: mechanic/Tabela: repair:
 
 Banco de dados: **PostgreSQL**

 *SQL:* (executado automaticamente pelo Docker via `docker/init.sql` — não precisa rodar manualmente)
 
```sql
CREATE TABLE repair (
    id          SERIAL PRIMARY KEY,
    namem       VARCHAR(100) NOT NULL,
    namec       VARCHAR(100) NOT NULL,
    description  TEXT NOT NULL,
    completed   CHAR(1) NOT NULL CHECK (completed IN ('s', 'n')),
    date        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    price       VARCHAR(100) NOT NULL
);
```

> Observação: o PostgreSQL não possui `ENUM` inline como o MySQL; aqui usamos `CHAR(1)` com uma restrição `CHECK`. O `AUTO_INCREMENT` é substituído por `SERIAL`.

 *img:*
 
![alt text](https://github.com/rafaelbucard/oficina2.0/blob/main/img_readme/Tabela.png)  


  
  
### Composer/ Autoload :

LINK: https://getcomposer.org/

O `composer install` é executado **dentro do build do Docker** (ver `Dockerfile`), gerando o autoload PSR-4. Não é necessário rodar Composer manualmente.

obs: não está sendo utilizada nenhuma biblioteca além do Autoload.

composer.json 
``
{
   
    "autoload": {
        "psr-4": {
            "App\\": "app/"
        }
    }
}``

 ### Desktop:
 
 ![alt text](https://github.com/rafaelbucard/oficina2.0/blob/main/img_readme/oficinahome.png)  


 ### Responsividade para Mobile:
 

![alt text](https://github.com/rafaelbucard/oficina2.0/blob/main/img_readme/delete_id.png)  


![alt text](https://github.com/rafaelbucard/oficina2.0/blob/main/img_readme/mobile_cadastro.png)  


![alt text](https://github.com/rafaelbucard/oficina2.0/blob/main/img_readme/home_table.png)  


