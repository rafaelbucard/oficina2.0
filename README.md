# Oficina2.0 :wrench: :wrench:
 Sistema para oficina mecânica (cadastro de orçamento)
 
 Autor: Rafael Buçard 


Crud OOP com sistema de busca e filtro  feito em PHP7 respeitando a PSR-4.  
 
 **(O Objetivo do Projeto é demonstrar habilidades com a linguagem PHP , integração com banco de dados MySQL, Bootstrap e gerenciador de dependências Composer, para criação de um crud orientado a objeto simples, rápido e visualmente adequado e responsivo, além de criar uma ferramenta usual para o dia dia de uma oficina mecânica.)**
 
### Ferramentas:
* PHP: 7.4.11
* MySQL
* Composer
* BootstrapCDN
### Abrindo Localmente:
* Todo ambiente configurado com o Xampp
 LINK: https://www.apachefriends.org/pt_br/index.html  
 
   
 ### BootstrapCDN:
 
LINK: https://getbootstrap.com.br/docs/4.1/getting-started/introduction/  

   
 ### Base de dados: mechanic/Tabela: repair:
 
 Versão do cliente de base de dados: libmysql - mysqlnd 7.4.11
 Tipo de dados para a criação da tabela na imagem abaixo.
 
 *SQL:*
 
 Crie um banco chamado: mechanic.
 
 Depois execute :
 
 
`` CREATE TABLE `mechanic`.`repair` ( `id` INT NOT NULL AUTO_INCREMENT , `namem` VARCHAR(100) NOT NULL , `namec` VARCHAR(100) NOT NULL , `description` TEXT NOT NULL , `completed` ENUM('s', 'n') NOT NULL , `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `price` VARCHAR(100) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB; 
 ``

 *img:*
 
![alt text](https://github.com/rafaelbucard/oficina2.0/blob/main/img_readme/Tabela.png)  


  
  
### Composer/ Autoload :

LINK: https://getcomposer.org/


criar aquivo Json (já criado )

terminal:  composer install

obs: não está sendo utilizada nem uma bibliotéca além do Autoload 

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


