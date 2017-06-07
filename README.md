# Extensão Pagarme para Magento 2 (Magento CE 2+)
Módulo para meio de pagamento Pagarme Magento versão 2. 

## Instalação
### Instalar usando o [composer](https://getcomposer.org/):

1. Entre na pasta raíz da sua instalação
2. Digite o seguinte comando:
    ```bash
    composer require roeder/pagarme
    ```
3. Para baixar a biblioteca do Pagarme:
    ```bash
    composer require pagarme/pagarme-php:2.0
    ```
4. Digite os seguintes comandos, no terminal, para habilitar o módulo:
    ```bash
    php bin/magento module:enable Roeder_Pagarme --clear-static-content
    php bin/magento setup:upgrade
    ```
### ou baixar e instalar manualmente:


* Criar a seguinte estrutura de pastas app/code/Roeder/Pagarme
* Baixe a ultima versão [aqui](https://codeload.github.com/brunoroeder/magento2-Pagarme/zip/master)
* Descompacte o arquivo baixado e copie as pastas para dentro do diretório criado no início
* Digite os seguintes comandos, no terminal, para habilitar o módulo:

    ```bash
    php bin/magento module:enable Roeder_Pagarme --clear-static-content
    php bin/magento setup:upgrade
    ```
    

OBSERVAÇÕES
===========

**Este módulo atualmente suporta apenas BOLETO** - Atualmente encontra-se em desenvolvimento e suporta apenas o meio de pagamento Boleto, seja livre para contribuir e se desejar continuar o desenvolvimento para Cartão de Crédito e/ou outras funcionalidades.
