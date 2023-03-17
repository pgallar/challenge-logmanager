# Challenge Logmanager

## Requisitos
<ol>
  <li>Verificar que los puertos 3306, 8000 e 3000 não estejam em uso</li>
    <li>Docker e docker-compose</li>
</ol>


## Instruções
<ol>
    <li>Clone este repositório e navegue até a pasta raiz do projeto.</li>
    <li>Execute o comando docker-compose up para iniciar os serviços do projeto.</li>
    <li>Acesse o site 'https://developers.mercadolivre.com.br/devcenter' e registre a App no Mercado Livre, utilizando os seguintes dados:</li>
    <li>URL Redirect: http://localhost:8000/accounts/callback/APP-SHORT-NAME (substitua APP-SHORT-NAME pelo nome curto da sua aplicação)</li>
    <li>Selecione os Escopos: read, offline access</li>
    <li>Selecione as permissões: Orders_v2, Orders Feedback</li>
    <li>URL de retornos de chamada de notificação: https://localhost:8000/api/order-notifications (os serviços não aceitam https, para isso é necessário adquirir um certificado SSL e hospedar os serviços em um domínio)</li>
    <li>Acesse a URL 'http://localhost:3000'</li>
    <li>Registre uma nova conta clicando no botão 'Nova Conta'</li>
    <li>Carregue os dados da conta Client ID, Client Secret e Short Name e clique em 'Criar'</li>
    <li>Aceite e vincule a conta à aplicação criada no Mercado Livre</li>
    <li>Simule o envio de uma notificação de pedido usando ferramentas como POSTMAN.</li>
</ol>

## Simulação POSTMAN
> Metodo: POST
> Body JSON exemplo (trocar os dados correspondientes): 
>> {
>>  "_id":"f9f08571-1f65-4c46-9e0a-c0f43faas1557e",   
>>  "resource":"/orders/2000004912352524",
>>  "user_id": 734908421,
>>  "topic":"orders_v2",
>>  "application_id": 7980210790598964,
>>  "attempts":1,
>>  "sent":"2019-10-30T16:19:20.129Z",
>>  "received":"2019-10-30T16:19:20.106Z"
>>}