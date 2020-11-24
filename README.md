# Oficina2.0 :wrench:
 Sistema para oficina mecanica (cadastro de orçamento)
 
 Autor: Rafael Buçard 


Crud OOP com sistema de busca e filtro  feito em PHP7 respeitando a PSR-4.  
 
 **(O Objetivo do Projeto é demonstrar habilidades com a linguagem PHP , integração com banco de dados MySQL, Bootstrap e gerenciador de dependências Composer, além de criar uma ferramenta usual para o dia dia de uma oficina mecanica.)**
# Ferramentas:
* PHP: 7.4.11
* MySQL
* Composer
* BootstrapCDN
## Abrindo Localmente:
* Todo ambiente configurado com o Xampp
 LINK: https://www.apachefriends.org/pt_br/index.html  
 
   
 ## BootstrapCDN:
 
LINK: https://getbootstrap.com.br/docs/4.1/getting-started/introduction/  

   
 ## Base de dados/Tabela:`repair`:
 
 Versão do cliente de base de dados: libmysql - mysqlnd 7.4.11

![alt text](https://github.com/rafaelbucard/oficina2.0/blob/main/Tabela.png)  


  
## Composer/ Autoload :

LINK: https://getcomposer.org/


criar aquivo Json (já criado )

terminal:  composer install

obs: não está sendo utilizada nem uma bibliotéca além do Autoload 

composer.json 

{
   
    "autoload": {
        "psr-4": {
            "App\\": "app/"
        }
    }
}

## Referências :
PHP: Programando com Orientação a Objetos
Autor: Pablo Dall'Oglio. Editora: Novatec. Ano 2018 (4ed).

Consulta sobre pesquisas e filtros : https://www.youtube.com/wdevoficial
