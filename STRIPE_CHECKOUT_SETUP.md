# Configuração do Stripe Checkout

Este documento contém instruções para configurar o Stripe Checkout e o webhook para processamento de pagamentos.

## Requisitos

1. Conta no Stripe (https://stripe.com)
2. Chaves de API do Stripe (Pública e Secreta)

## Configuração do .env

Adicione as seguintes variáveis ao seu arquivo `.env`:

```
STRIPE_KEY=pk_test_sua_chave_publica_de_teste
STRIPE_SECRET=sk_test_sua_chave_secreta_de_teste
STRIPE_WEBHOOK_SECRET=whsec_seu_segredo_do_webhook
```

## Configuração do Webhook

1. Acesse o [Dashboard do Stripe](https://dashboard.stripe.com/)
2. Vá para Developers > Webhooks
3. Clique em "Add endpoint"
4. Adicione a URL do seu webhook: `https://seu-site.com/webhook/stripe`
5. Selecione os eventos que deseja escutar, no mínimo:
   - `checkout.session.completed`
6. Clique em "Add endpoint"
7. Copie o "Signing secret" e adicione-o ao seu arquivo `.env` como `STRIPE_WEBHOOK_SECRET`

## Testando o Checkout

Para testar o checkout, você pode usar os cartões de teste do Stripe:

- Pagamento bem-sucedido: `4242 4242 4242 4242`
- Pagamento que requer autenticação: `4000 0025 0000 3155`
- Pagamento recusado: `4000 0000 0000 9995`

Para todos os cartões de teste, você pode usar:
- Qualquer data de validade futura (MM/AA)
- Qualquer código CVC de 3 dígitos
- Qualquer CEP válido

## Fluxo de Pagamento

1. O usuário seleciona produtos e vai para o checkout
2. Ao escolher pagamento com cartão, o usuário é redirecionado para o Stripe Checkout
3. Após o pagamento, o Stripe redireciona o usuário de volta para a página de sucesso
4. O webhook do Stripe notifica a aplicação sobre o status do pagamento
5. A aplicação atualiza o status do pedido no banco de dados

## Solução de Problemas

### Testando Webhooks Localmente

Para testar webhooks em ambiente de desenvolvimento, você pode usar o [Stripe CLI](https://stripe.com/docs/stripe-cli):

```bash
stripe listen --forward-to localhost:8000/webhook/stripe
```

Isso fornecerá um webhook secret temporário que você pode usar no seu arquivo `.env`.

### Logs de Webhook

Verifique os logs do Stripe Dashboard para depurar problemas com webhooks:

1. Acesse o [Dashboard do Stripe](https://dashboard.stripe.com/)
2. Vá para Developers > Webhooks
3. Selecione seu endpoint
4. Clique em "Logs" para ver os eventos enviados e as respostas recebidas