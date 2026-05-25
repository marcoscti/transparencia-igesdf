<?php

/**
 * Plugin Name: Transparência IgesDF
 * Description: Plugin para exibir dados de transparência do IgesDF, como despesas, receitas e contratos.
 * Version: 1.0.0
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Author: Marcos Cordeiro
 * Author URI: https://github.com/marcoscti
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

if (!defined('ABSPATH')) {
    exit;
}

/*
|--------------------------------------------------------------------------
| CONSTANTES
|--------------------------------------------------------------------------
*/

define('TRANSPARENCIA_IGESDF_URL', plugin_dir_url(__FILE__));
define('TRANSPARENCIA_IGESDF_VERSION', '1.0.0');


/*
|--------------------------------------------------------------------------
| DASHICONS FRONTEND
|--------------------------------------------------------------------------
*/
/**
 * Enfileira os scripts e estilos do frontend
 */
function transparencia_igesdf_enqueue_assets()
{
    wp_enqueue_style('dashicons');
    wp_enqueue_style(
        'transparencia-igesdf-style',
        TRANSPARENCIA_IGESDF_URL . 'assets/css/transparencia-igesdf.css',
        [],
        TRANSPARENCIA_IGESDF_VERSION
    );
    wp_enqueue_script(
        'transparencia-igesdf-script',
        TRANSPARENCIA_IGESDF_URL . 'assets/js/transparencia-igesdf.js',
        ['jquery'], // Adicionado jQuery como dependência
        TRANSPARENCIA_IGESDF_VERSION,
        true
    );
}
add_action('wp_enqueue_scripts', 'transparencia_igesdf_enqueue_assets');

/*
|--------------------------------------------------------------------------
| REGISTRAR CPT E TAXONOMIA
|--------------------------------------------------------------------------
*/

function transparencia_igesdf_registrar_estruturas()
{
    register_post_type('transparencia_igesdf', [

        'labels' => [
            'name'               => 'Transparência IgesDF',
            'singular_name'      => 'Transparência',
            'add_new'            => 'Adicionar Novo',
            'add_new_item'       => 'Adicionar Novo Conteúdo',
            'edit_item'          => 'Editar Conteúdo',
            'new_item'           => 'Novo Conteúdo',
            'view_item'          => 'Visualizar Conteúdo',
            'search_items'       => 'Buscar Conteúdo',
            'not_found'          => 'Nenhum conteúdo encontrado',
            'not_found_in_trash' => 'Nenhum conteúdo encontrado na lixeira',
            'menu_name'          => 'Transparência',
        ],

        'public'              => true,
        'publicly_queryable'  => true,
        'exclude_from_search' => false,
        'has_archive'         => false,
        'hierarchical'        => true,
        'menu_icon'           => 'dashicons-chart-bar',

        'supports' => [
            'title',
            'editor',
            'thumbnail',
            'excerpt',
            'page-attributes',
        ],

        'rewrite' => [
            'slug'       => 'transparencia-iges',
            'with_front' => false,
        ],

        'show_in_rest' => true,
    ]);


    /*
    |--------------------------------------------------------------------------
    | TAXONOMIA ANO
    |--------------------------------------------------------------------------
    */

    register_taxonomy('ano', ['transparencia_igesdf'], [

        'labels' => [
            'name'              => 'Anos',
            'singular_name'     => 'Ano',
            'search_items'      => 'Buscar Ano',
            'all_items'         => 'Todos os Anos',
            'edit_item'         => 'Editar Ano',
            'update_item'       => 'Atualizar Ano',
            'add_new_item'      => 'Adicionar Ano',
            'new_item_name'     => 'Novo Ano',
            'menu_name'         => 'Anos',
        ],

        'public'             => true,
        'hierarchical'       => false,
        'show_admin_column'  => true,
        'show_in_rest'       => true,
    ]);
}

add_action('init', 'transparencia_igesdf_registrar_estruturas');


/*
|--------------------------------------------------------------------------
| METABOX
|--------------------------------------------------------------------------
*/

function transparencia_igesdf_registrar_metabox()
{
    add_meta_box(
        'transparencia_igesdf_data',
        'Dados da Transparência',
        'transparencia_igesdf_render_metabox',
        'transparencia_igesdf',
        'side',
        'default'
    );
}

add_action('add_meta_boxes', 'transparencia_igesdf_registrar_metabox');


/*
|--------------------------------------------------------------------------
| RENDER METABOX
|--------------------------------------------------------------------------
*/

function transparencia_igesdf_render_metabox($post)
{
    wp_nonce_field(
        'transparencia_igesdf_salvar_metabox',
        'transparencia_igesdf_nonce'
    );

    $mes_salvo = get_post_meta($post->ID, '_mes', true);

    $link_csv = get_post_meta($post->ID, '_csv_url', true);

    $termos_ano = wp_get_post_terms($post->ID, 'ano');

    $ano_salvo = !empty($termos_ano)
        ? $termos_ano[0]->term_id
        : '';

    $meses = [
        'Janeiro',
        'Fevereiro',
        'Março',
        'Abril',
        'Maio',
        'Junho',
        'Julho',
        'Agosto',
        'Setembro',
        'Outubro',
        'Novembro',
        'Dezembro'
    ];

    $anos = get_terms([
        'taxonomy'   => 'ano',
        'hide_empty' => false,
    ]);

?>

    <div style="display:flex;flex-direction:column;gap:16px;">

        <!-- LINK CSV -->
        <div>

            <label for="campo_csv">
                <strong>Link CSV</strong>
            </label>

            <input
                type="url"
                name="campo_csv"
                id="campo_csv"
                value="<?php echo esc_attr($link_csv); ?>"
                placeholder="https://site.com/arquivo.csv"
                style="width:100%;margin-top:5px;"
                required
                >
        </div>

        <!-- MÊS -->
        <div>

            <label for="campo_mes">
                <strong>Mês</strong>
            </label>

            <select
                name="campo_mes"
                id="campo_mes"
                style="width:100%;margin-top:5px;"
                required>

                <option value="">
                    Selecione
                </option>

                <?php foreach ($meses as $mes): ?>

                    <option
                        value="<?php echo esc_attr($mes); ?>"
                        <?php selected($mes_salvo, $mes); ?>>

                        <?php echo esc_html($mes); ?>

                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <!-- ANO -->
        <div>

            <label for="campo_ano">
                <strong>Ano</strong>
            </label>

            <select
                name="campo_ano"
                id="campo_ano"
                style="width:100%;margin-top:5px;"
                required>

                <option value="">
                    Selecione
                </option>

                <?php foreach ($anos as $ano): ?>

                    <option
                        value="<?php echo esc_attr($ano->term_id); ?>"
                        <?php selected($ano_salvo, $ano->term_id); ?>>

                        <?php echo esc_html($ano->name); ?>

                    </option>

                <?php endforeach; ?>

            </select>

        </div>

    </div>

<?php
}


/*
|--------------------------------------------------------------------------
| CONTEÚDO PERSONALIZADO
|--------------------------------------------------------------------------
*/

function transparencia_igesdf_conteudo_personalizado($content)
{
    if (!is_singular('transparencia_igesdf')) {
        return $content;
    }

    global $post;

    $link_csv = get_post_meta($post->ID, '_csv_url', true);

    if (!$link_csv) {
        return $content;
    }

    ob_start();

?>

    <div class="transparencia-arquivo">

        <a
            href="<?php echo esc_url($link_csv); ?>"
            class="transparencia-download"
            target="_blank">
            <span class="dashicons dashicons-download"></span>
            Baixar Planilha .CSV (UTF-8)
        </a>

    </div>

    <div class="transparencia-tabela">

        <?php
        echo do_shortcode(
            '[csv_table url="' . esc_url($link_csv) . '"]'
        );
        ?>

    </div>

<?php

    $extra = ob_get_clean();

    return $content . $extra;
}

add_filter(
    'the_content',
    'transparencia_igesdf_conteudo_personalizado'
);


/*
|--------------------------------------------------------------------------
| SALVAR METABOX
|--------------------------------------------------------------------------
*/

function transparencia_igesdf_salvar_metabox($post_id)
{
    /*
    |--------------------------------------------------------------------------
    | VALIDAR NONCE
    |--------------------------------------------------------------------------
    */

    if (!isset($_POST['transparencia_igesdf_nonce'])) {
        return;
    }

    if (
        !wp_verify_nonce(
            $_POST['transparencia_igesdf_nonce'],
            'transparencia_igesdf_salvar_metabox'
        )
    ) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | AUTOSAVE
    |--------------------------------------------------------------------------
    */

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | PERMISSÃO
    |--------------------------------------------------------------------------
    */

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | SALVAR LINK CSV
    |--------------------------------------------------------------------------
    */

    if (isset($_POST['campo_csv'])) {

        update_post_meta(
            $post_id,
            '_csv_url',
            esc_url_raw($_POST['campo_csv'])
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SALVAR MÊS
    |--------------------------------------------------------------------------
    */

    if (isset($_POST['campo_mes'])) {

        update_post_meta(
            $post_id,
            '_mes',
            sanitize_text_field($_POST['campo_mes'])
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SALVAR ANO
    |--------------------------------------------------------------------------
    */

    if (
        isset($_POST['campo_ano']) &&
        !empty($_POST['campo_ano'])
    ) {

        wp_set_post_terms(
            $post_id,
            [intval($_POST['campo_ano'])],
            'ano'
        );
    }
}

add_action('save_post', 'transparencia_igesdf_salvar_metabox');


/*
|--------------------------------------------------------------------------
| FLUSH REWRITE AO ATIVAR
|--------------------------------------------------------------------------
*/

function transparencia_igesdf_ativar_plugin()
{
    transparencia_igesdf_registrar_estruturas();

    flush_rewrite_rules();
}

register_activation_hook(
    __FILE__,
    'transparencia_igesdf_ativar_plugin'
);


/*
|--------------------------------------------------------------------------
| FLUSH REWRITE AO DESATIVAR
|--------------------------------------------------------------------------
*/

function transparencia_igesdf_desativar_plugin()
{
    flush_rewrite_rules();
}

register_deactivation_hook(
    __FILE__,
    'transparencia_igesdf_desativar_plugin'
);

/*
|--------------------------------------------------------------------------
| SHORTCODE TRANSPARÊNCIA
|--------------------------------------------------------------------------
*/

function transparencia_igesdf_shortcode($atts)
{
    $atts = shortcode_atts([
        'hierarquia' => '',
    ], $atts);

    /*
    |--------------------------------------------------------------------------
    | VALIDAR HIERARQUIA
    |--------------------------------------------------------------------------
    */

    if (empty($atts['hierarquia'])) {
        return '';
    }

    /*
    |--------------------------------------------------------------------------
    | BUSCAR PÁGINA PAI
    |--------------------------------------------------------------------------
    */

    // Busca o pai tanto no CPT quanto em Páginas normais para evitar erro de "Hierarquia não encontrada"
    $pagina_pai = get_page_by_path(
        $atts['hierarquia'],
        OBJECT,
        ['transparencia_igesdf', 'page']
    );

    if (!$pagina_pai) {
        return '<p>Hierarquia não encontrada.</p>';
    }

    /*
    |--------------------------------------------------------------------------
    | BUSCAR ANOS
    |--------------------------------------------------------------------------
    */

    $anos = get_terms([
        'taxonomy'   => 'ano',
        'hide_empty' => true,
        'orderby'    => 'name',
        'order'      => 'DESC',
    ]);

    if (empty($anos)) {
        return '<p>Nenhum ano encontrado.</p>';
    }

    ob_start();

?>

    <div class="transparencia-accordion">

        <?php foreach ($anos as $ano): ?>

            <?php

            /*
            |--------------------------------------------------------------------------
            | BUSCAR POSTS
            |--------------------------------------------------------------------------
            */

            $query = new WP_Query([
                'post_type'      => 'transparencia_igesdf',
                'post_parent'    => $pagina_pai->ID,
                'posts_per_page' => -1,
                'orderby'        => 'date',
                'order'          => 'DESC',

                'tax_query' => [
                    [
                        'taxonomy' => 'ano',
                        'field'    => 'term_id',
                        'terms'    => $ano->term_id,
                    ]
                ]
            ]);

            ?>

            <?php if ($query->have_posts()): ?>

                <div class="transparencia-item">

                    <button
                        class="transparencia-toggle"
                        type="button">

                        <?php echo esc_html($pagina_pai->post_title . ' ' . $ano->name); ?>

                        <span class="transparencia-icon">
                            <span class="dashicons dashicons-arrow-down-alt2"></span>
                        </span>

                    </button>

                    <div class="transparencia-content">

                        <ul>

                            <?php while ($query->have_posts()): ?>

                                <?php $query->the_post(); ?>

                                <li>

                                    <a href="<?php the_permalink(); ?>">

                                        <?php the_title(); ?>

                                    </a>

                                </li>

                            <?php endwhile; ?>

                        </ul>

                    </div>

                </div>
            <?php endif; ?>

            <?php wp_reset_postdata(); ?>

        <?php endforeach; ?>

    </div>

<?php

    return ob_get_clean();
}

add_shortcode(
    'transparencia',
    'transparencia_igesdf_shortcode'
);
