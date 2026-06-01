=== Transparência IgesDF ===
Contributors: marcoscti
Tags: transparencia, igesdf, csv, data, accordion
Requires at least: 5.8
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Plugin para gerenciamento e exibição de planilhas de transparência do IgesDF, como despesas, receitas e contratos.

== Description ==

O **Transparência IgesDF** permite organizar documentos de prestação de contas de forma hierárquica e cronológica. Ele registra um Tipo de Post Personalizado (CPT) dedicado à transparência, com suporte a categorização por anos.

Principais recursos:
* Registro de documentos com link para arquivos CSV.
* Organização por Taxonomia de "Anos".
* Shortcode para exibição em sanfona (accordion).
* Filtro automático de conteúdo para exibição de tabelas.

== Installation ==

1. Envie a pasta `transparencia-igesdf` para o diretório `/wp-content/plugins/`.
2. Ative o plugin através do menu 'Plugins' no WordPress.
3. **Importante:** Certifique-se de ter um plugin que suporte o shortcode `[csv_table]` instalado para a renderização das tabelas.

== FAQ ==

= O plugin funciona sozinho para exibir tabelas? =
Não. Este plugin gerencia a estrutura e os links. Para exibir o conteúdo dos arquivos CSV em formato de tabela, ele depende da presença do plugin que provê o shortcode `[csv_table]`.

== Shortcode ==

Utilize `[transparencia hierarquia="slug-da-pagina-pai"]` para listar os posts de transparência organizados por ano que sejam filhos de uma página específica.

== Screenshots ==

1. Interface administrativa com campos de Mês, Ano e link CSV.
2. Visualização em Accordion no frontend.

== Changelog ==

= 1.0.0 =
* Versão inicial com CPT, Taxonomia e Shortcode de Accordion.

== Upgrade Notice ==

= 1.0.0 =
Versão inicial estável.
