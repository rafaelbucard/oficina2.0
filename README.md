# Oficina2.0 :wrench:
 Sistema para oficina mecanica (cadastro de orçamento)
 Autor: Rafael Buçard 


Crud com sistema de busca e filtro  feito em PHP7 respeitando a PSR-4.  
 
 **(O Objetivo do Projeto é demonstrar habilidades com a linguagem PHP , integração com banco de dados MySQL e gerenciador de dependências Composer)**
# Ferramentas:
* PHP 7
* MySQL
* Composer
# Abrindo Localmente:
* Todo ambiente configurado com o Xampp
 LINK: https://www.apachefriends.org/pt_br/index.html
 
 # Base de dados/Tabela:`repair`:
 
![alt text](https://github.com/rafaelbucard/oficina2.0/blob/main/Tabela.png)

# Composer/ Autoload :
criar aquivo Json (já criado )

terminal:  composer install

obs: não está sendo utilizada nem uma biblioteca alem do Autoload 

composer.json 

{
   
    "autoload": {
        "psr-4": {
            "App\\": "app/"
        }
    }
}
