# Transparência IgesDF

Plugin WordPress desenvolvido para gerenciar e exibir dados de transparência institucional (receitas, despesas, contratos, etc) do IgesDF através de arquivos CSV.

## 🚀 Recursos

- **CPT Transparência:** Gerenciamento centralizado de documentos.
- **Taxonomia de Anos:** Organização cronológica simplificada.
- **Metaboxes Customizadas:** Campos específicos para URL do CSV e seleção de Mês/Ano.
- **Shortcode de Accordion:** Exibição elegante e compacta dos dados organizada por hierarquia.
- **Integração Dashicons:** Uso de ícones nativos do WordPress para interface do usuário.

## 📋 Pré-requisitos / Dependências

Para que a funcionalidade de exibição de dados funcione plenamente, este plugin requer:

1. **Shortcode `[csv_table]`:** O plugin assume a existência de um renderizador de CSV externo. Recomenda-se o uso de um plugin de tabelas CSV que suporte o atributo `url`.
2. **WordPress:** Versão 5.8 ou superior.
3. **PHP:** Versão 7.4 ou superior.

## 🛠️ Instalação

1. Clone o repositório em seu diretório de plugins:
   ```bash
   git clone https://github.com/marcoscti/transparencia-igesdf.git
   ```
2. Ative o plugin no painel administrativo do WordPress.
3. Crie os termos na taxonomia "Anos" antes de publicar seus primeiros documentos.

## 📖 Como usar

### Configuração de Posts
1. Vá em **Transparência > Adicionar Novo**.
2. Preencha o título e o conteúdo (se desejar).
3. No painel lateral, informe a **URL do arquivo CSV**, o **Mês** e o **Ano**.

### Exibição no Site
Utilize o shortcode abaixo em qualquer página ou post:

```text
[transparencia hierarquia="nome-da-pagina-pai"]
```

*Onde `hierarquia` deve ser o slug de um post de transparência que serve como "pai" para os itens que você deseja listar.*

## 📄 Licença

Este projeto está licenciado sob a licença GPLv2 ou posterior.

---
**Desenvolvido por:** Marcos Cordeiro