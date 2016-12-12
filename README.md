#Magento Extension for Pagar.me Payment Gateway (Magento CE 2+)
Módulo para meio de pagamento Pagarme Magento versão 2. 

## Instalação
### Instalar usando o [composer](https://getcomposer.org/):

1. Entre na pasta raíz da sua instalação
2. Digite o seguinte comando[
    ```bash
    composer require roeder/pagarme
    ```
    
3. Digite os seguintes comandos, no terminal, para habilitar o módulo:

    ```bash
    php bin/magento module:enable Roeder_Pagarme --clear-static-content
    php bin/magento setup:upgrade
    ```
### ou baixar e instalar manualmente:


* Criar a seguinte estrutura de pastas app/code/Roeder/Pagarme
* ixe a ultima versão [aqui](https://codeload.github.com/brunoroeder/magento2-Pagarme/zip/master)
* compacte o arquivo baixado e copie as pastas para dentro do diretório criado no inicio
* Digite os seguintes comandos, no terminal, para habilitar o módulo:
    ```bash
    php bin/magento module:enable Roeder_Pagarme --clear-static-content
    php bin/magento setup:upgrade
    ```
    

OBSERVAÇÕES
===========

**Este módulo atualmente suporta apenas BOLETO** - Atualmente está em desenvolvimento e suporta apenas o meio de pagamento Boleto, seja livre para conribuir se desejar continuar o desenvolvimento para Cartão de Crédito.
