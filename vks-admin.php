<?php

// Register the plugin page
function vks_admin_menu() {

	add_menu_page( __( 'Easy Digital Downloads & Товары ВКонтакте', 'vkshop-for-edd' ), __( 'Товары ВК', 'vkshop-for-edd' ), 'activate_plugins', 'vkshop', 'vks_vk_api_settings_page', null, '90' );
}

add_action( 'admin_menu', 'vks_admin_menu', 20 );


function vks_settings_admin_init() {
	global $vks_settings;

	$vks_settings = new WP_Settings_API_Class2_Edd;

	$tabs = array(
		'vks_options' => array(
			'id'       => 'vks_options',
			'name'     => 'vks_options',
			'title'    => __( 'Настройки', 'vkshop-for-edd' ),
			'desc'     => __( '', 'vkshop-for-edd' ),
			'sections' => array(

				'vks_market_section'  => array(
					'id'    => 'vks_market_section',
					'name'  => 'vks_market_section',
					'title' => __( 'Настройки страницы ВК', 'vkshop-for-edd' ),
					'desc'  => __( 'Настройки страницы ВКонтакте на которой будут размещены товары.', 'vkshop-for-edd' ),
				),
				'vks_options_section' => array(
					'id'    => 'vks_options_section',
					'name'  => 'vks_options_section',
					'title' => __( 'Настройки синхронизации', 'vkshop-for-edd' ),
					'desc'  => __( 'Настройки синхронизации товаров edd с товарами в группе ВКонтакте.', 'vkshop-for-edd' ),
				),

			)
		)
	);
	$tabs = apply_filters( 'vks_settings_tabs', $tabs, $tabs );

	$fields = array(
		'vks_market_section'  => array(
			array(
				'name'  => 'page_url',
				'label' => __( 'Ссылка на страницу', 'vkshop-for-edd' ),
				'desc'  => __( 'Урл страницы, на которой вы будете размещать товары.
        <br/>Например: <code>http://vk.com/pasportvzubi</code>.
        <br/><br/>Вы можете создать <a href="http://vk.com/public.php?act=new" target="_blank">новую страницу</a> ВКонтакте или найти среди ваших уже <a href="http://vk.com/groups?tab=admin" target="_blank">созданных страниц</a>.', 'vkshop-for-edd' ),
				'type'  => 'text'
			),
			array(
				'name'     => 'page_id',
				'label'    => __( 'ID страницы ВКонтакте', 'vkshop-for-edd' ),
				'desc'     => __( 'Значение будет подставлено автоматически.
				<br><br>Если значение не появилось, нужно: навести курсор на поле с урлом группы, кликнуть левой кнопкой мыши, затем кликнуть левой кнопкой мыши в любом месте страницы - появится знак ожидания и id группы.
				<br>Если значение все еще не появилось, нужно открыть <a href="' . admin_url( '/admin.php?page=vkshop-log' ) . '">Лог плагина</a>, там могут отображаться возможные ошибки.
				<br>Если из Лога неясно в чем дело, можно написать в <a href="https://vk.me/wordpressvk">службу поддержки</a>.', 'vkshop-for-edd' ),
				'type'     => 'text',
				'readonly' => true
			),
			array(
				'name'     => 'page_screen_name',
				'label'    => __( 'Короткое имя', 'vkshop-for-edd' ),
				'desc'     => __( 'Значение будет подставлено автоматически.', 'vkshop-for-edd' ),
				'type'     => 'text',
				'readonly' => true
			),
			array(
				'name'    => 'timeout',
				'label'   => __( 'Timeout', 'vkshop-for-edd' ),
				'desc'    => __( '<b>Внимание!</b> Служебные настройки. Менять только в <a href="https://wordpress.org/plugins/vkshop-for-edd/">указанном случае</a>.', 'vkshop-for-edd' ),
				'type'    => 'text',
				'default' => '5',
			)
		),
		'vks_options_section' => array(
			array(
				'name'    => 'sync',
				'label'   => __( 'Синхронизация', 'vkshop-for-edd' ),
				'desc'    => __( 'Включить или отключить синхронизацию товаров на сайте с товарами в группе ВК.', 'vkshop-for-edd' ),
				'type'    => 'radio',
				'default' => '0',
				'options' => array(
					'1' => __( 'Включена', 'vkshop-for-edd' ),
					'0' => __( 'Отключена', 'vkshop-for-edd' )
				)
			),
			array(
				'name'    => 'vks_category',
				'label'   => __( 'Рубрика', 'vkshop-for-edd' ),
				'desc'    => __( 'Рубрика в Товарах ВК в которой по умолчанию будут размещаться товары с сайта.', 'vkshop-for-edd' ),
				'type'    => 'select',
				'default' => '0',
				'options' => vks_get_vk_categories( null, true )
			),
			array(
				'name'    => 'vks_album_filtered',
				'label'   => __( 'Подборки', 'vkshop-for-edd' ),
				'desc'    => __( '<small>Доступно в <a href = "javascript:void(0);" class = "get-vks-pro">PRO версии</a>.</small>
		<br>Фото категорий, у которых есть родительская, будут загружены в ВК в черно-белом варианте с 50% прозрачностью. <b>Зачем?</b> Для создания <a href="#">эффекта вложенных подборок</a>.', 'vkshop-for-edd' ),
				'type'    => 'multicheck',
				'options' => array(
					'1' => 'Включить псевдовложенные подборки',
				)
			),
			array(
				'name'    => 'message',
				'label'   => __( 'Описание товара', 'vkshop-for-edd' ),
				'desc'    => __( 'Маска для описания товара в разделе Товары ВК:
        <br/><code>%content%</code> - полное описание товара,
        <br/><code>%excerpt%</code> - краткое описание товара (excerpt) или описание до тега <code>' . esc_html( '<!--more-->' ) . '</code>,
        <br/><code>%link%</code> - ссылка на товар,
        <br/><code>%sku%</code> - артикул товара (если артикул не задан - id товара на сайте).
        <br/>
        <br/><small>Доступно в <a href = "javascript:void(0);" class = "get-vks-pro">PRO версии</a>.</small>
        <br/><code>%specs%</code> - спецификации товара (Требуется плагин Easy Digital Downloads — Specs),
        <br/><code>%addToCartLink%</code> - ссылка на товар в корзине: при клике, товар автоматически помещается в корзину, и открывается страница оформления заказа.', 'vkshop-for-edd' ),
				'type'    => 'textarea',
				'default' => "%content%"
			),
			array(
				'name'     => 'message_default',
				'desc'     => __( '<small>Доступно в <a href = "javascript:void(0);" class = "get-vks-pro">PRO версии</a>.</small>
		<br>Если у продукта нет описания (включая свойства, вариации и все, что указано в предыдущей опции), будет добавлен данный текст. <b>Зачем?</b> ВК требует, чтобы у продукта было описание не менее 10 символов.', 'vkshop-for-edd' ),
				'type'     => 'text',
				'default'  => 'Описание отсутствует.',
				'readonly' => true
			),
			array(
				'name'    => 'button_buy_url',
				'label'   => __( 'Кнопка "Купить" в ВК', 'vkshop-for-edd' ),
				'desc'    => __( 'На странице товара в ВК, вместо кнопки "Написать продавцу", можно показать кнопку "Купить товар", после нажатия которой пользователь будет перенаравлен по заданной ссылке. Ссылка может вести на <code>товар на сайте</code> или <code>товар в корзине</code> (в этом случае пользователю останется только оформить заказ).', 'vkshop-for-edd' ),
				'type'    => 'radio',
				'default' => '0',
				'options' => array(
					'0'       => __( 'Не использовать', 'vkshop-for-edd' ),
					'download' => __( 'Ссылка на страницу товара', 'vkshop-for-edd' ),
					'add_to_cart'    => __( 'Ссылка на товар в корзине (<small><em>доступно в <a href = "javascript:void(0);" class = "get-vks-pro">PRO версии</a>.</em></small>)', 'vkshop-for-edd' )
				)
			),
			array(
				'name'    => 'button_buy_url_short',
				'desc'    => __( '<small>Доступно в <a href = "javascript:void(0);" class = "get-vks-pro">PRO версии</a>.</small>
		<br>Ссылка на товар или товар в корзине будет сокращена через сервис <code>vk.сс</code>. Статистика переходов по ссылке будет видна только Вам на странице <a href="https://vk.com/cc" target="_blank">https://vk.com/cc</a>.
		<br><b>Зачем?</b> Оригинальная ссылка может быть очень длинной, а ВК допускает только ссылки короче 120 знаков.', 'vkshop-for-edd' ),
				'type'    => 'multicheck',
				'options' => array(
					'1' => 'Сократить ссылку',
				)
			),
		),

	);
	$fields = apply_filters( 'vks_settings_fields', $fields, $fields );

	//set sections and fields
	$vks_settings->set_option_name( 'vks_options' );
	$vks_settings->set_sections( $tabs );
	$vks_settings->set_fields( $fields );

	//initialize them
	$vks_settings->admin_init();
}

add_action( 'admin_init', 'vks_settings_admin_init' );


function vks_settings_admin_menu() {
	global $vks_settings_page;

	$vks_settings_page = add_submenu_page( 'vkshop', __( 'Настройки Товаров ВК ', 'vkshop-for-edd' ), __( 'Настройки', 'vkshop-for-edd' ), 'activate_plugins', 'vkshop-settings', 'vks_settings_page' );

	add_action( 'admin_footer-' . $vks_settings_page, 'vks_settings_page_js' );
}

add_action( 'admin_menu', 'vks_settings_admin_menu', 25 );


function vks_settings_page_js() {
	?>
	<script type="text/javascript">
		jQuery(document).ready(function ($) {

			$("#vks_options\\[page_url\\]").focusout(function () {
				var data = {
					action: 'vks_get_group_id',
					group_url: $("#vks_options\\[page_url\\]").val()
				};

				$.ajax({
					url: ajaxurl,
					data: data,
					type: "POST",
					dataType: 'json',
					beforeSend: function () {
						$("#vks_options\\[page_url\\]\\[spinner\\]").css({
							'display': 'inline-block',
							'visibility': 'visible'
						});
					},
					success: function (data) {
						$("#vks_options\\[page_url\\]\\[spinner\\]").hide();
						//if (data['gid'] < 0)
						//  data['gid'] = -1 * data['gid'];
						$("#vks_options\\[page_id\\]").val(data['gid']);
						$("#vks_options\\[page_screen_name\\]").val(data['screen_name']);

						//console.log(data);
					}
				});
			});

		}); // jQuery End
	</script>
	<?php
}


function vks_settings_page() {
	global $vks_settings;
	$options = get_option( 'vks_vk_api_site' );

	?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"><br/></div>
		<h2><?php _e( 'Easy Digital Downloads & Товары ВКонтакте', 'vkshop-for-edd' ); ?></h2>

		<?php
		if ( ! isset( $options['site_access_token'] ) || empty( $options['site_access_token'] ) ) {
			?>
			<div class="error">
				<p>
					<?php _e( 'Необходимо настроить VK API. Откройте страницу "<a href="' . admin_url( 'admin.php?page=vkshop' ) . '">Настройки VK API</a>".', 'vkshop-for-edd' ); ?>
				</p>
			</div>
			<?php
		}
		?>

		<div id="col-container">
			<div id="col-right" class="vks">
				<div class="vks-box">
					<?php vks_admin_sticky(); ?>
				</div>
			</div>
			<div id="col-left" class="vks">
				<?php
				settings_errors();
				$vks_settings->show_navigation();
				$vks_settings->show_forms();
				?>
			</div>
		</div>
	</div>
	<?php
}


function vks_log_admin_init() {
	global $vks_log;

	$vks_log = new WP_Settings_API_Class2_Edd;

	$tabs = array(
		'vks_log' => array(
			'id'            => 'vks_log',
			'name'          => 'vks_log',
			'title'         => __( 'Лог', 'vkshop-for-edd' ),
			'desc'          => __( '', 'vkshop-for-edd' ),
			'submit_button' => false,
			'sections'      => array(
				'vks_log_section' => array(
					'id'    => 'vks_log_section',
					'name'  => 'vks_log_section',
					'title' => __( 'Лог действий плагина', 'vkshop-for-edd' ),
					'desc'  => __( '<div>' . vks_the_log( 100 ) . '</div>', 'vkshop-for-edd' ),
				)
			)
		)
	);

	$fields = array();

	//set sections and fields
	$vks_log->set_option_name( 'vks_options' );
	$vks_log->set_sections( $tabs );
	$vks_log->set_fields( $fields );

	//initialize them
	$vks_log->admin_init();
}

add_action( 'admin_init', 'vks_log_admin_init' );


// Register the plugin page
function vks_log_admin_menu() {
	global $vks_log_settings_page;

	$vks_log_settings_page = add_submenu_page( 'vkshop', 'Лог', 'Лог', 'activate_plugins', 'vkshop-log', 'vks_log_page' );
}

add_action( 'admin_menu', 'vks_log_admin_menu', 60 );

// Display the plugin settings options page
function vks_log_page() {
	global $vks_log;

	?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"><br/></div>
		<h2><?php _e( 'Лог плагина Easy Digital Downloads & Товары ВК', 'vkshop-for-edd' ); ?></h2>

		<div id="col-container">
			<div id="col-right" class="vks">
				<div class="vks-box">
					<?php vks_admin_sticky(); ?>
				</div>
			</div>
			<div id="col-left" class="vks">
				<?php
				settings_errors();
				$vks_log->show_navigation();
				$vks_log->show_forms();
				?>
			</div>
		</div>
	</div>
	<?php
}


/* Помощь по работе с плагином */

function vks_help_admin_init() {
	global $vks_help;

	$vks_help = new WP_Settings_API_Class2_Edd;

	$tabs = array(
		'vks_help' => array(
			'id'            => 'vks_help',
			'name'          => 'vks_help',
			'title'         => __( 'Помощь', 'vkshop-for-edd' ),
			'desc'          => __( '', 'vkshop-for-edd' ),
			'submit_button' => false,
			'sections'      => array(

				'vks_help_section' => array(
					'id'    => 'vks_help_section',
					'name'  => 'vks_help_section',
					'title' => __( 'Настройки и начало работы', 'vkshop-for-edd' ),
					'desc'  => __( 'Настройки и начало работы с плагином Товары ВК для Easy Digital Downloads.', 'vkshop-for-edd' ),
				)
			)
		)
	);


	$fields = array(
		'vks_help_section' => array(
			array(
				'name'  => 'vk_group_settings',
				'label' => __( 'Настройки группы ВКонтакте', 'vkshop-for-edd' ),
				'desc'  => __( 'В группе, куда планируется экспортировать товары, нужно:
				<ol><li>Открыть меню <b>Управление сообществом</b> - <b>Разделы</b>,</li>
				<li>Поставить галочки в опциях <b>Фотографии</b> (иначе будет невозможно отправить фото товаров) и <b>Товары</b>,</li>
				<li>Нажать кнопку <b>Сохранить</b>.</li></ol>
				Чтобы заменить кнопку "<em>Написать продавцу</em>" на "<em>Купить</em>" на странице товара в ВК:
				<ol><li>Открыть меню <b>Управление сообществом</b> - <b>Разделы</b>,</li>
				<li>В блоке <b>Товары</b>, опция <b>Тип кнопки</b>: выбрать <b>Ссылка на товар</b>,</li>
				<li>Нажать кнопку <b>Сохранить</b>.</li></ol>', 'vkshop-for-edd' ),
				'type'  => 'html'
			),
			array(
				'name'  => 'plugin_vkapi_settings',
				'label' => __( 'Настройки VK API в плагине', 'vkshop-for-edd' ),
				'desc'  => __( 'В меню плагина <b>Товары ВК</b> - <a href="' . admin_url( '/admin.php?page=vkshop' ) . '" target="_blank">Настройки VK API</a>, следуя описанным там инструкциям, нужно:
				<ol><li>Создать приложение ВКонтакте и настроить его,</li>
				<li>Получить токен,</li>
				<li>Нажать кнопку <b>Сохранить</b>.</li></ol>', 'vkshop-for-edd' ),
				'type'  => 'html'
			),
			array(
				'name'  => 'plugin_settings',
				'label' => __( 'Настройки плагина', 'vkshop-for-edd' ),
				'desc'  => __( 'В меню плагина <b>Товары ВК</b> - <a href="' . admin_url( '/admin.php?page=vkshop-settings' ) . '" target="_blank">Настройки</a>, нужно:
				<ol><li>В опции <b>Ссылка на страницу</b> ввести адрес группы ВК, после этого кликнуть левой кнопкой мыши в любом месте сайта (появится знак ожидания, затем ID и короткое имя страницы),
				<li>В опции <b>Синхронизация</b> выбрать Включено,</li>
				<li>В опции <b>Рубрика</b> задать категорию в ВК в которую будут отправляться товары с сайта,</li>
				<li>В опции <b>Кнопка "Купить" в ВК</b> выбрать куда будет перенаправлен пользователь после нажатия,</li>
				<li>Нажать кнопку <b>Сохранить</b>.</li></ol>', 'vkshop-for-edd' ),
				'type'  => 'html'
			),
			array(
				'name'  => 'product_export',
				'label' => __( 'Отправить товар в группу', 'vkshop-for-edd' ),
				'desc'  => __( 'Чтобы отправить товар в группу, нужно:
				<ol><li><a href="' . admin_url( '/edit.php?post_type=download' ) . '" target="_blank">Открыть</a> любой товар в режиме редактирования,
				<li>Нажать кнопку <b>Обновить</b> (большая синяя кнопка в блоке Опубликовать). Товар будет отправлен в группу.</li>
				</ol>', 'vkshop-for-edd' ),
				'type'  => 'html'
			),
			array(
				'name'  => 'errors',
				'label' => __( 'Отладка', 'vkshop-for-edd' ),
				'desc'  => __( 'Если что-то идет не так, нужно:
				<ol><li>Открыть в меню плагина <b>Товары ВК</b> - <a href="' . admin_url( '/admin.php?page=vkshop-log' ) . '" target="_blank">Лог</a>, там могут отображаться возможные ошиибки,
				<li>Обратиться в <a href="https://wordpress.org/support/plugins/vkshop-for-edd/" target="_blank">службу поддержки</a>, описав проблему и, по возможности, приведя сообщения из Лога.</li>
				</ol>', 'vkshop-for-edd' ),
				'type'  => 'html'
			),
			array(
				'name'  => 'documentation',
				'label' => __( 'Документация', 'vkshop-for-edd' ),
				'desc'  => __( '<a href="https://wordpress.org/plugins/vkshop-for-edd/" target="_blank">Руководство</a> по работе с плагином.', 'vkshop-for-edd' ),
				'type'  => 'html'
			),
		)
	);

	$is_pro = vks_is_pro();

	if ( ! $is_pro ) {

		$fields['vks_help_section'][] = array(
			'name'  => 'get_pro',
			'label' => __( 'Больше возможностей', 'vkshop-for-edd' ),
			'desc'  => __( '<b>Товары ВКонтакте PRO для Easy Digital Downloads</b> поддерживает:
			<ol><li><strong>массовые операции с товарами</strong>: экспорт, удаление из группы ВК,</li>
		 	<li>все действия с <strong>подборками товаров ВК</strong>: создание, изменение, удаление, перемещение, поддержка псевдовложенных подборок,</li>
		  	<li>и многое другое.</li></ol>
			' . get_submit_button( 'Узнать больше', 'primary', 'get-vks-pro', false ), 'vkshop-for-edd' ),
			'type'  => 'html'
		);
	}


	$fields = apply_filters( 'vks_help_fields', $fields, $fields );

	//$fields = array();

	//set sections and fields
	$vks_help->set_option_name( 'vks_options' );
	$vks_help->set_sections( $tabs );
	$vks_help->set_fields( $fields );

	//initialize them
	$vks_help->admin_init();
}

add_action( 'admin_init', 'vks_help_admin_init' );


// Register the plugin page
function vks_help_admin_menu() {
	global $vks_help_settings_page;

	$vks_help_settings_page = add_submenu_page( 'vkshop', 'Помощь', 'Помощь', 'activate_plugins', 'vkshop-help', 'vks_help_page' );
}

add_action( 'admin_menu', 'vks_help_admin_menu', 70 );


// Display the plugin settings options page
function vks_help_page() {
	global $vks_help;
	?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"><br/></div>
		<h2><?php _e( 'Настройки и начало работы', 'vkshop-for-edd' ); ?></h2>

		<div id="col-container">
			<div id="col-right" class="vks">
				<div class="vks-box">
					<?php vks_admin_sticky(); ?>
				</div>
			</div>
			<div id="col-left" class="vks">
				<?php
				settings_errors();
				$vks_help->show_navigation();
				$vks_help->show_forms();
				?>
			</div>
		</div>
	</div>
	<?php
}

/* END */


function vks_bulk_admin_init() {
	global $vks_bulk;

	$vks_bulk = new WP_Settings_API_Class2_Edd;

	$tabs = array(
		'vks_bulk'           => array(
			'id'            => 'vks_bulk',
			'name'          => 'vks_bulk',
			'title'         => __( 'Экспорт & Удаление', 'vkshop-for-edd' ),
			'desc'          => __( '', 'vkshop-for-edd' ),
			'submit_button' => false,
			'sections'      => array(

				'vks_export_section' => array(
					'id'    => 'vks_export_section',
					'name'  => 'vks_export_section',
					'title' => __( 'Экспорт / Удаление', 'vkshop-for-edd' ),
					'desc'  => __( 'Массовый экспорт или удаление товаров из группы ВКонтакте.', 'vkshop-for-edd' ),
				),


			)
		),
		'vks_bulk_reactions' => array(
			'id'            => 'vks_bulk_reactions',
			'name'          => 'vks_bulk_reactions',
			'title'         => __( 'Подборки & Товары', 'vkshop-for-edd' ),
			'desc'          => __( '', 'vkshop-for-edd' ),
			'submit_button' => false,
			'sections'      => array(

				'vks_bulk_reactions_section' => array(
					'id'    => 'vks_bulk_reactions_section',
					'name'  => 'vks_bulk_reactions_section',
					'title' => __( 'Подборки & Товары', 'vkshop-for-edd' ),
					'desc'  => __( 'Обновление очередности подборок в разделе Товары в группе ВК и обновление товаров в подборках ВК.', 'vkshop-for-edd' ),
				),


			)
		),
	);
	$tabs = apply_filters( 'vks_bulk_tabs', $tabs, $tabs );

	$fields = array(
		'vks_export_section'         => array(
			array(
				'name'    => 'action',
				'label'   => __( 'Действие', 'vkshop-for-edd' ),
				'desc'    => __( '<small>Доступно в <a href = "javascript:void(0);" class = "get-vks-pro">PRO версии</a>.</small><br>Действия с товарами или подборками.', 'vkshop-for-edd' ),
				'type'    => 'radio',
				'default' => 'export',
				'options' => array(
					'export'      => __( 'Экспорт товаров в ВК', 'vkshop-for-edd' ),
					'delete'      => __( 'Удаление товаров из ВК', 'vkshop-for-edd' ),
					'update'      => __( 'Обновление товаров в ВК', 'vkshop-for-edd' ),
					'term_export' => __( 'Экспорт подборок в ВК', 'vkshop-for-edd' ),
					'term_delete' => __( 'Удаление подборок из ВК', 'vkshop-for-edd' )
				)
			),
			array(
				'name' => 'vks_updated',
				'desc' => __( '<small>Доступно в <a href = "javascript:void(0);" class = "get-vks-pro">PRO версии</a>.</small>
			<br>Обновить записи, опубликованные ранее указанной даты (в формате <code>' . gmdate( 'Y-m-d H:i:s', current_time( 'timestamp' ) ) . '</code>).', 'vkshop-for-edd' ),
				'type' => 'text'
			),
			array(
				'name'  => 'product_cats',
				'label' => __( 'Категории товаров', 'vkshop-for-edd' ),
				'desc'  => __( '<small>Доступно в <a href = "javascript:void(0);" class = "get-vks-pro">PRO версии</a>.</small><br>Выберите категорию, товары из которой нужно отправить или удалить из группы ВК. ', 'vkshop-for-edd' ),
				'type'  => 'select_product_checklist'
			),
			array(
				'name'    => 'product_cats_select_all',
				'desc'    => __( '<small>Доступно в <a href = "javascript:void(0);" class = "get-vks-pro">PRO версии</a>.</small><br>Выделить все категории или снять выделение со всех категорий.', 'vkshop-for-edd' ),
				'type'    => 'multicheck',
				'options' => array(
					'1' => 'Выделить все',
				)
			),
			array(
				'name'    => 'posts_per_page',
				'label'   => __( 'Количество', 'vkshop-for-edd' ),
				'desc'    => __( '<small>Доступно в <a href = "javascript:void(0);" class = "get-vks-pro">PRO версии</a>.</small><br>Сколько объектов экспортировать, обновить или удалить из / в группы ВК.', 'vkshop-for-edd' ),
				'default' => '1',
				'type'    => 'text'
			),
			array(
				'name'    => 'order',
				'label'   => __( 'Порядок', 'vkshop-for-edd' ),
				'desc'    => __( '<small>Доступно в <a href = "javascript:void(0);" class = "get-vks-pro">PRO версии</a>.</small><br>Порядок в котором будут отправлены товары (сортировка по дате создания товара на сате).', 'vkshop-for-edd' ),
				'type'    => 'radio',
				'default' => 'desc',
				'options' => array(
					'desc' => __( 'От новых к старым', 'vkshop-for-edd' ),
					'asc'  => __( 'От старых к новым', 'vkshop-for-edd' )
				)
			),
			array(
				'name' => 'export',
				'desc' => __( '<small>Доступно в <a href = "javascript:void(0);" class = "get-vks-pro">PRO версии</a>.</small><br><br>', 'vkshop-for-edd' ) .
				          get_submit_button( __( 'Начать', 'vkshop-for-edd' ), 'primary', 'vks_export_button', false, 'disabled' ) . '&nbsp;&nbsp;' . '&nbsp;&nbsp;' .
				          get_submit_button( __( 'Остановить', 'vkshop-for-edd' ), 'secondary', 'vks_export_stop_button', false, 'disabled' ) . '&nbsp;&nbsp;' . '&nbsp;&nbsp;' .
				          '<span id="vks_export_ajax[spinner]" style="float:none !important; margin: 0 5px !important;" class="spinner"></span>
				           <span id="vks_export_msg"></span>',
				'type' => 'html'
			)

		),
		'vks_bulk_reactions_section' => array(

			array(
				'name'    => 'action',
				'label'   => __( 'Действие', 'vkshop-for-edd' ),
				'desc'    => __( '<small>Доступно в <a href = "javascript:void(0);" class = "get-vks-pro">PRO версии</a>.</small>
					<br>При обновлении подборок, плагин приведет взаимное расположение подборок в группе в соовтетствие со взаимным расположением соответствующих категорий на сайте.
					<br>При обновлении товаров в подборках, плагин добавит товары в ВК в соответствующие подборки.
					<br><br><strong>Внимание!</strong> При обновлении, эта страница сайта должна оставаться открытой.
					', 'vkshop-for-edd' ),
				'type'    => 'radio',
				'default' => 'reorder',
				'options' => array(
					'reorder' => __( 'Обновить подборки', 'vkshop-for-edd' ),
					'readd'   => __( 'Обновить товары в подборках', 'vkshop-for-edd' )
				)
			),
			array(
				'name' => 'reset_albums_order',
				'desc' => get_submit_button( __( 'Сбросить', 'vkshop-for-edd' ), 'secondary', 'vks_reset_albums_order_button', false, 'disabled' ) . '&nbsp;&nbsp;' . '&nbsp;&nbsp;' .
				          '<span id="vks_reset_albums_order_ajax[spinner]" style="float:none !important; margin: 0 5px !important;" class="spinner"></span>
				          <p class = "description"><small>Доступно в <a href = "javascript:void(0);" class = "get-vks-pro">PRO версии</a>.</small>
					<br>Сбросить порядок подборок в группе.</p>',
				'type' => 'html'
			),
			array(
				'name' => 'reset_errors',
				'desc' => get_submit_button( __( 'Сбросить ошибки', 'vkshop-for-edd' ), 'secondary', 'vks_reset_errors_button', false, 'disabled' ) . '&nbsp;&nbsp;' . '&nbsp;&nbsp;' .
				          '<span id="vks_reset_errors_ajax[spinner]" style="float:none !important; margin: 0 5px !important;" class="spinner"></span>
				          <p class = "description"><small>Доступно в <a href = "javascript:void(0);" class = "get-vks-pro">PRO версии</a>.</small>
					<br>Сбросить ошибки (будут удалены служебные сообщения об ошибках, при неудачной попытке отправить товар).</p>',
				'type' => 'html'
			),
			array(
				'name' => 'reaction',
				'desc' => __( '<small>Доступно в <a href = "javascript:void(0);" class = "get-vks-pro">PRO версии</a>.</small><br><br>', 'vkshop-for-edd' ) .
				          get_submit_button( __( 'Начать', 'vkshop-for-edd' ), 'primary', 'vks_reaction_button', false, 'disabled' ) . '&nbsp;&nbsp;' . '&nbsp;&nbsp;' .
				          get_submit_button( __( 'Остановить', 'vkshop-for-edd' ), 'secondary', 'vks_reaction_stop_button', false, 'disabled' ) . '&nbsp;&nbsp;' . '&nbsp;&nbsp;' .
				          '<span id="vks_reaction_ajax[spinner]" style="float:none !important; margin: 0 5px !important;" class="spinner"></span>
				           <span id="vks_reaction_msg"></span>',
				'type' => 'html'
			)
		)

	);
	$fields = apply_filters( 'vks_bulk_fields', $fields, $fields );

	//set sections and fields
	$vks_bulk->set_option_name( 'vks_bulk' );
	$vks_bulk->set_sections( $tabs );
	$vks_bulk->set_fields( $fields );

	//initialize them
	$vks_bulk->admin_init();
}

add_action( 'admin_init', 'vks_bulk_admin_init' );


// Register the plugin page
function vks_bulk_admin_menu() {
	global $vks_bulk_page;

	$vks_bulk_page = add_submenu_page( 'vkshop', 'Действия', 'Действия', 'activate_plugins', 'vkshop-bulk', 'vks_bulk_page' );
}

add_action( 'admin_menu', 'vks_bulk_admin_menu', 50.01 );


// Display the plugin settings options page
function vks_bulk_page() {
	global $vks_bulk;

	?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"><br/></div>
		<h2><?php _e( 'Массовые действия с товарами', 'vkshop-for-edd' ); ?></h2>

		<div id="col-container">
			<div id="col-right" class="vks">
				<div class="vks-box">
					<?php vks_admin_sticky(); ?>
				</div>
			</div>
			<div id="col-left" class="vks">
				<?php
				settings_errors();
				$vks_bulk->show_navigation();
				$vks_bulk->show_forms();
				?>
			</div>
		</div>
	</div>
	<?php
}


function vks_download_category_add_form_fields() {
	$is_pro = vks_is_pro();

	?>
	<div class="form-field">
		<label for="vks_category"><?php _e( 'Категория в Товары ВК', 'vkshop-for-edd' ); ?></label>
		<select id="vks_category" name="vks_category" class="postform">
			<?php
			echo vks_vk_categories_select_helper();
			?>
		</select>

		<p>
			<strong>Необязательно.</strong> Категория в Товары ВК, в которую будут отправляться товары из данной категории на сайте.
		</p>
	</div>

	<div class="form-field">
		<label for="vks_album">
			<input type="checkbox" id="vks_album" name="vks_album" value="1" <?php if ( ! $is_pro ) { ?>disabled="disabled"<?php } ?>>
			Это <strong>подборка</strong> в Товары ВК
		</label>

		<p><?php if ( ! $is_pro ) { ?>
				<small>Доступно в <a href="javascript:void(0);" class="get-vks-pro">PRO версии</a>.</small><br>
			<?php } ?>
			Если отмечено, в Товары ВК будет создана подборка с соответствующим "Названием" и "Миниатюрой".
			<br>Если было отмечено ранее, а теперь - нет, подборка из Товары ВК будет удалена.
		</p>
	</div>

	<div class="form-field">
		<label for="vks_main_album">
			<input type="checkbox" id="vks_main_album" name="vks_main_album" value="1" <?php if ( ! $is_pro ) { ?>disabled="disabled"<?php } ?> >
			Это <strong>основная</strong> подборка в Товары ВК
		</label>

		<p><?php if ( ! $is_pro ) { ?>
				<small>Доступно в <a href="javascript:void(0);" class="get-vks-pro">PRO версии</a>.</small><br>
			<?php } ?>
			Если отмечено, подборка станет основной и первые 3 товара из нее будут видны в блоке Товары над записями на главной странице группы.
			<br>Если было отмечено ранее, а теперь - нет, подборка сохранится но уже не будет основной.
		</p>
	</div>
	<?php
}

add_action( 'download_category_add_form_fields', 'vks_download_category_add_form_fields' );


function vks_download_category_edit_form_fields( $term ) {
	$is_pro = vks_is_pro();

	if ( function_exists( 'get_term_meta' ) ) {
		$vks_category = get_term_meta( $term->term_id, 'vks_category', true );
		$vk_item_id   = get_term_meta( $term->term_id, 'vk_item_id', true );
	}

	$vk_album_link = '';
	$vk_album      = false;
	$vk_main_album = 0;
	if ( ! empty( $vk_item_id ) ) {
		$vk_album      = true;
		$_vk_item_id   = explode( '_', $vk_item_id );
		$vk_album_url  = 'https://vk.com/market' . $_vk_item_id[0] . '?section=album_' . $_vk_item_id[1];
		$vk_album_link = '/ <small><a href="' . $vk_album_url . '" target="_blank">' . $vk_album_url . '</a></small>';

		if ( function_exists( 'get_term_meta' ) ) {
			$vk_main_album = get_term_meta( $term->term_id, 'vk_main_album', true );
		}
	}

	?>
	<tr class="form-field">
		<th scope="row" valign="top"><label><?php _e( 'Категория в Товары ВК', 'vkshop-for-edd' ); ?></label>
		</th>
		<td>
			<select id="vks_category" name="vks_category" class="postform">
				<?php
				echo vks_vk_categories_select_helper( $vks_category );
				?>
			</select>

			<p>
				<strong>Необязательно.</strong> Категория в Товары ВК, в которую будут отправляться товары из данной категории на сайте.
			</p>
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row" valign="top"></th>
		<td>
			<label for="vks_album">
				<input type="checkbox" id="vks_album" name="vks_album" value="1" <?php checked( $vk_album, true );
				if ( ! $is_pro ) { ?>disabled="disabled"<?php } ?>>
				Это <strong>подборка</strong> в Товары ВК <?php echo $vk_album_link; ?>
			</label>

			<p class="description"><?php if ( ! $is_pro ) { ?>
					<small>Доступно в <a href="javascript:void(0);" class="get-vks-pro">PRO версии</a>.</small><br>
				<?php } ?>
				Если отмечено, в Товары ВК будет создана подборка с соответствующим "Названием" и "Миниатюрой".
				<br>Если было отмечено ранее, а теперь - нет, подборка из Товары ВК будет удалена.
			</p>
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row" valign="top"></th>
		<td>
			<label for="vks_main_album">
				<input type="checkbox" id="vks_main_album" name="vks_main_album" value="1" <?php checked( $vk_main_album, 1 );
				if ( ! $is_pro ) { ?>disabled="disabled"<?php } ?> >
				Это <strong>основная</strong> подборка в Товары ВК
			</label>

			<p class="description"><?php if ( ! $is_pro ) { ?>
					<small>Доступно в <a href="javascript:void(0);" class="get-vks-pro">PRO версии</a>.</small><br>
				<?php } ?>
				Если отмечено, подборка станет основной и первые 3 товара из нее будут видны в блоке Товары над записями на главной странице группы.
				<br>Если было отмечено ранее, а теперь - нет, подборка сохранится но уже не будет основной.
			</p>
		</td>
	</tr>

	<?php
}

add_action( 'download_category_edit_form_fields', 'vks_download_category_edit_form_fields', 10 );


function vks_created_term( $term_id, $tt_id = '', $taxonomy = '' ) {
	if ( isset( $_POST['vks_category'] ) && 'download_category' === $taxonomy ) {
		
		$sanitizevkscat = sanitize_text_field( $_POST['vks_category'] );

		if ( function_exists( 'get_term_meta' ) ) {
			if ( ! update_term_meta( $term_id, 'vks_category', esc_attr( $sanitizevkscat ) ) ) {

				add_term_meta( $term_id, 'vks_category', esc_attr( $sanitizevkscat ), true );
			}
		} else {
			if ( ! update_edd_term_meta( $term_id, 'vks_category', esc_attr( $sanitizevkscat ) ) ) {

				add_edd_term_meta( $term_id, 'vks_category', esc_attr( $sanitizevkscat ), true );
			}
		}
	}
}

add_action( 'created_term', 'vks_created_term', 10, 3 );
add_action( 'edit_term', 'vks_created_term', 10, 3 );


function vks_manage_edit_download_category_columns( $columns ) {
	$columns['vks_category'] = __( 'Товары ВК', 'vkshop-for-edd' );

	return $columns;
}

add_filter( 'manage_edit-download_category_columns', 'vks_manage_edit_download_category_columns' );


function vks_manage_download_category_custom_column( $columns, $column, $id ) {
	global $vk_market_categories;

	if ( 'vks_category' == $column ) {
		if ( function_exists( 'get_term_meta' ) ) {
			$cat_id = get_term_meta( $id, 'vks_category', true );
		} 
		$name = '';

		if ( $cat_id && ! empty( $vk_market_categories[ $cat_id ] ) ) {

			$name = $vk_market_categories[ $cat_id ];
		}

		$columns .= $name;
	}

	return $columns;
}

add_filter( 'manage_download_category_custom_column', 'vks_manage_download_category_custom_column', 10, 3 );


/* TODO
function vks_post_row_actions( $actions, $post ) {

	if ( $post->post_type == 'product' ) {
		$vk_item_id = get_post_meta( $post->ID, 'vk_item_id', true );
		$out        = array();

		$actions['vks_sync'] = '<a href = "javascript:void(0);" data-owner_id =' . $vk_item_id . '  class = "vks_sync">TO VK Market</a>';
	}

	return $actions;
}

add_filter( 'post_row_actions', 'vks_post_row_actions', 10, 2 );
*/

function vks_download_posts_custom_column( $column, $post_id ) {
	global $post;

	$vk_item_id = get_post_meta( $post->ID, 'vk_item_id', true );

	switch ( $column ) {
		case "vk_market":
			$vk_item_url = '';
			if ( ! empty( $vk_item_id ) ) {
				$_vk_item_id = explode( '_', $vk_item_id );
				$vk_item_url = 'https://vk.com/market' . $_vk_item_id[0] . '?w=product' . $vk_item_id;
				printf( __( '<a href="%s" target="_blank">Есть</a>', 'vkshop-for-edd' ), $vk_item_url );
			} else {
				_e( 'Нет', 'vkshop-for-edd' );
			}

			break;
	}
}

add_action( 'manage_posts_custom_column', 'vks_download_posts_custom_column', 10, 2 );


function vks_download_posts_columns( $columns ) {

	$columns['vk_market'] = __( 'Товары ВК', 'vkshop-for-edd' );

	return $columns;
}

add_filter( 'manage_edit-download_columns', 'vks_download_posts_columns' );

//добавим сортировку
function vks_sortable_download_columns( $columns ) {
	
	$columns['vk_market']    = 'vk_market';
	
	return $columns;
}
add_filter( 'manage_edit-download_sortable_columns', 'vks_sortable_download_columns' );

function vks_sort_downloads( $vars ) {
	
	if ( isset( $vars['post_type'] ) && 'download' == $vars['post_type'] ) {
		
		if ( isset( $vars['orderby'] ) && 'vk_market' == $vars['orderby'] ) {
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'vk_item_id',
					'orderby'  => 'meta_value_num'
				)
			);
		}
	}

	return $vars;
}

function vks_vk_api_settings_admin_init() {
	global $vks_vk_api_settings;

	$vks_vk_api_settings = new WP_Settings_API_Class2_Edd;

	$options = get_option( 'vks_vk_api_site' );

	$tabs = array(
		'vks_vk_api_site' => array(
			'id'       => 'vks_vk_api_site',
			'name'     => 'vks_vk_api_site',
			'title'    => __( 'VK API', 'vkshop-for-edd' ),
			'desc'     => __( '', 'vkshop-for-edd' ),
			'sections' => array(
				'vks_vk_api_site_section' => array(
					'id'    => 'vks_vk_api_site_section',
					'name'  => 'vks_vk_api_site_section',
					'title' => __( 'Настройки VK API', 'vkshop-for-edd' ),
					'desc'  => __( 'Создание приложения ВКонтакте и подключение его к сайту.', 'vkshop-for-edd' ),
				)
			)
		),
	);

	$url     = site_url();
	$url2    = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );
	$url_arr = explode( ".", basename( $url2 ) );
	$domain  = $url_arr[ count( $url_arr ) - 2 ] . "." . $url_arr[ count( $url_arr ) - 1 ];

	$site_app_id_desc = '<p>Чтобы получить доступ к <b>API ВКонтакте</b>, вам нужно <a href="http://vk.com/editapp?act=create" target="_blank">создать приложение</a> со следующими настройками:</p>
  <ol>
    <li><strong>Название:</strong> любое</li>
    <li><strong>Тип:</strong> Веб-сайт</li>
    <li><strong>Адрес сайта:</strong> ' . $url . '</li>
    <li><strong>Базовый домен:</strong> ' . $domain . '</li>
  </ol>
  <p>Если приложение с этими настройками у вас было создано ранее, вы можете найти его на <a href="https://vk.com/apps?act=manage" target="_blank">странице приложений</a> и, затем нажмите "Редактировать", чтобы открылись его параметры.</p>
  <p>В полях ниже вам нужно указать: <b>ID приложения</b> и его <b>Защищенный ключ</b>.</p>';

	$site_get_access_token_url = ( ! empty( $options['site_app_id'] ) ) ? vks_vk_login_url() : 'javascript:void(0);';

	$site_access_token_desc = '<p>Чтобы получить <strong>Access Token</strong>:</p>
  <ol>
    <li>Пройдите по <a href="' . $site_get_access_token_url . '" id = "getaccesstokenurl">ссылке</a></li>
    <li>Подтвердите уровень доступа.</li>
  </ol>';


	$fields = array(
		'vks_vk_api_site_section' => array(
			array(
				'name' => 'site_app_id_desc',
				'desc' => __( $site_app_id_desc, 'vkshop-for-edd' ),
				'type' => 'html',
			),
			array(
				'name'  => 'site_app_id',
				'label' => __( 'ID приложения', 'evc' ),
				'desc'  => __( 'ID вашего приложения VK.', 'vkshop-for-edd' ),
				'type'  => 'text'
			),
			array(
				'name'  => 'site_app_secret',
				'label' => __( 'Защищенный ключ', 'evc' ),
				'desc'  => __( 'Защищенный ключ вашего приложения VK.', 'vkshop-for-edd' ),
				'type'  => 'text'
			),
		),

	);

	if ( ! empty( $options['site_app_id'] ) && ! empty( $options['site_app_secret'] ) ) {

		array_push(
			$fields['vks_vk_api_site_section'],
			array(
				'name' => 'site_access_token_desc',
				'desc' => __( $site_access_token_desc, 'vkshop-for-edd' ),
				'type' => 'html',
			),
			array(
				'name'     => 'site_access_token',
				'label'    => __( 'Access Token', 'evc' ),
				'desc'     => __( 'Значение будет подставлено автоматически, как только вы пройдете по указанной выше ссылке.', 'vkshop-for-edd' ),
				'type'     => 'text',
				'readonly' => true
			)
		);

	}

	//set sections and fields
	$vks_vk_api_settings->set_option_name( 'vks_vk_api' );
	$vks_vk_api_settings->set_sections( $tabs );
	$vks_vk_api_settings->set_fields( $fields );

	//initialize them
	$vks_vk_api_settings->admin_init();
}

add_action( 'admin_init', 'vks_vk_api_settings_admin_init' );


// Register the plugin page
function vks_vk_api_admin_menu() {
	global $vks_vk_api_settings_page;

	$vks_vk_api_settings_page = add_submenu_page( 'vkshop', 'Настройки API ВКонтакте', 'Настройки VK API', 'activate_plugins', 'vkshop', 'vks_vk_api_settings_page' );

	//add_action( 'admin_footer-'. $vks_vk_api_settings_page, 'vks_vk_api_settings_page_js' );
}

add_action( 'admin_menu', 'vks_vk_api_admin_menu', 20 );


function vks_vk_api_settings_page_js() {
	?>
	<script type="text/javascript">
		jQuery(document).ready(function ($) {

			$("#evc_vk_api_autopost\\[app_id\\]").change(function () {
				if ($(this).val().trim().length) {
					$(this).val($(this).val().trim());
					$('#getaccesstokenurl').attr({
						'href': 'http://oauth.vk.com/authorize?client_id=' + $(this).val().trim() + '&scope=wall,photos,video,market,offline&redirect_uri=http://api.vk.com/blank.html&display=page&response_type=token',
						'target': '_blank'
					});

				}
				else {
					$('#getaccesstokenurl').attr({'href': 'javscript:void(0);'});
				}

			});

		}); // jQuery End
	</script>
	<?php
}


// Display the plugin settings options page
function vks_vk_api_settings_page() {
	global $vks_vk_api_settings;

	?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"><br/></div>
		<h2><?php _e( 'Настройки API ВКонтакте', 'vkshop-for-edd' ); ?></h2>

		<div id="col-container">
			<div id="col-right" class="vks">
				<div class="vks-box">
					<?php vks_admin_sticky(); ?>
				</div>
			</div>
			<div id="col-left" class="vks">
				<?php

				settings_errors();
				$vks_vk_api_settings->show_navigation();
				$vks_vk_api_settings->show_forms();
				?>
			</div>
		</div>
	</div>
	<?php
}


function vks_vk_login_url( $redirect_url = false, $echo = false ) {
	//$options = get_option('evc_options');
	$options = get_option( 'vks_vk_api_site' );

	if ( ! $redirect_url ) {
		$redirect_url = remove_query_arg( array(
			'code',
			'redirect_uri',
			'settings-updated',
			'loggedout',
			'error',
			'access_denied',
			'error_reason',
			'error_description',
			'reauth'
		), $_SERVER['REQUEST_URI'] );
		//$redirect_url = get_bloginfo('wpurl') . $redirect_url;
		$redirect_url = site_url( $redirect_url );
	}

	$params = array(
		'client_id'     => trim( $options['site_app_id'] ),
		'redirect_uri'  => $redirect_url,
		'display'       => 'popup',
		'response_type' => 'code',
		'scope'         => 'market,photos,offline'
	);
	$query  = http_build_query( $params );

	$out = VKSEDD_AUTHORIZATION_URL . '?' . $query;

	if ( $echo ) {
		echo $out;
	} else {
		return $out;
	}
}


function vks_vk_autorization() {

	if ( ! empty( $_GET['page'] ) && 'vkshop' == $_GET['page'] && false !== ( $token = vks_get_token() ) ) {
		$options = get_option( 'vks_vk_api_site' );

		if ( isset( $token['access_token'] ) && ! empty( $token['access_token'] ) ) {
			$options['site_access_token'] = $token['access_token'];
			update_option( 'vks_vk_api_site', $options );
		}
		$redirect = remove_query_arg( array( 'code' ), $_SERVER['REQUEST_URI'] );
		//print__r($redirect);
		wp_redirect( site_url( $redirect ) );
		exit;
	}
}

add_action( 'admin_init', 'vks_vk_autorization' );


function vks_get_token() {
	$options = get_option( 'vks_vk_api_site' );

	if ( ! empty( $_GET['page'] ) && 'vkshop' == $_GET['page'] && isset( $_GET['code'] ) && ! empty( $_GET['code'] ) ) {

		$_SERVER['REQUEST_URI'] = remove_query_arg( array( 'code' ), $_SERVER['REQUEST_URI'] );

		$params = array(
			'client_id'     => trim( $options['site_app_id'] ),
			'redirect_uri'  => site_url( $_SERVER['REQUEST_URI'] ),
			'client_secret' => $options['site_app_secret'],
			'code'          => $_GET['code']
		);
		$query  = http_build_query( $params );
		//print__r($query); //

		$data = wp_remote_get( VKSEDD_TOKEN_URL . '?' . $query );
		//print__r($data); //
		//exit;
		if ( is_wp_error( $data ) ) {
			//print__r($data); //
			//exit;
			return false;
		}

		$resp = json_decode( $data['body'], true );
		if ( isset( $resp['error'] ) ) {
			return false;
		}

		return $resp;
	}

	return false;
}

function vks_admin_sticky() {
	?>
	<div class="vks-boxx">
		<p><?php _e( '<a href="https://wordpress.org/plugins/vkshop-for-edd/" target="_blank">Руководство</a> по работе с плагином.', 'vkshop-for-edd' ); ?>
		</p>
	</div>
	<?php

	$is_pro = vks_is_pro();

	if ( ! $is_pro ) {
		?>
		<h3>Товары ВКонтакте PRO для Easy Digital Downloads</h3>
		<p>PRO версия плагина поддерживает
			<strong>массовые операции с товарами</strong>: экспорт и удаление из группы ВК; все действия с
			<strong>подборками товаров ВК</strong>: создание, изменение, удаление, перемещение, поддержка псевдовложенных подборок и многое другое.
		</p>
		<p> <?php echo get_submit_button( 'Узнать больше', 'primary', 'get-vks-pro', false ); ?></p>
		<?php
	}
}


function vks_admin_footer() {
	?>
	<script type="text/javascript">
		jQuery(document).ready(function ($) {

			$(document).on('click', '#get-vks-pro, .get-vks-pro', function (e) {
				e.preventDefault();
				window.open(
					'https://wordpress.org/plugins/vkshop-for-edd/',
					'_blank'
				);
			});

		}); // jQuery End
	</script>
	<?php
}

add_action( 'admin_footer', 'vks_admin_footer' );


function vks_edit_form_after_title( $post ) {
	$vk_item_id = get_post_meta( $post->ID, 'vk_item_id', true );

	if ( ! empty( $vk_item_id ) && $post->post_type == 'download' ) {
		$_vk_item_id = explode( '_', $vk_item_id );
		$vk_item_url = 'https://vk.com/market' . $_vk_item_id[0] . '?w=product' . $vk_item_id;
		?>
		<div id="vks-product-link">
			<?php _e( '<strong>Товары ВК:</strong> ', 'vkshop-for-edd' );
			printf( __( '<a href="%s" target="_blank">%s</a>', 'vkshop-for-edd' ), $vk_item_url, $vk_item_url ); ?>
		</div>
		<?php
	}

}

add_action( 'edit_form_after_title', 'vks_edit_form_after_title' );


// DELETE PRODUCT FROM VK GROUP

function vks_delete_product_check_box() {
	global $post;

	if ( $post->post_type != 'download' ) {
		return;
	}

	$vk_item_id = get_post_meta( $post->ID, 'vk_item_id', true );
	if ( empty( $vk_item_id ) ) {
		return;
	}

	?>
	<div class="misc-pub-section">
		<p>
			<input type="checkbox" name="vks_delete_product"/> <?php _e( '<span style="color: #a00;">Удалить</span> товар из ВК', 'vkshop-for-edd' ); ?>
		</p>

		<?php
		$vk_captcha = get_transient( 'vk_captcha' );

		if ( ! empty( $vk_captcha['vks_vkapi_market_add'] ) &&
		     'post' == $vk_captcha['vks_vkapi_market_add']['item_type'] &&
		     $post->ID == $vk_captcha['vks_vkapi_market_add']['item_id']
		) {
			?>
			<p><span style="color: #FF0000; border-bottom: 1px solid #FF0000;">Не опубликовано!</span>
				<br/><img src="<?php echo $vk_captcha['vks_vkapi_market_add']['captcha_img']; ?>" style="margin:10px 0 3px;"/>
				<br/><input type="hidden" name="captcha_sid" value="<?php echo $vk_captcha['vks_vkapi_market_add']['captcha_sid']; ?>"><input type="text" value="" autocomplete="off" size="16" class="form-input-tip" name="captcha_key">
				<br/>Введите текст с картинки, чтобы опубликовать товар ВКонтакте.</p>
			<?php
		}
		?>
	</div>
	<?php
}

add_action( 'post_submitbox_misc_actions', 'vks_delete_product_check_box' );

function vks_exclude_product_vk() {
	global $post;

	if ( $post->post_type != 'download' ) {
		return;
	}
	$vk_item_id = get_post_meta( $post->ID, 'vk_item_id', true );
	if ( $vk_item_id  ) {
		return;
	}
	
	$check = get_post_meta($post->ID, 'vks_exclude_product', true);

	?>
	<div class="misc-pub-section">
		<p>
			<input type="checkbox" value="1" <?php checked($check, true, true); ?> name="vks_exclude_product" />
			 <?php _e( '<span style="color: #a00;">Не отправлять</span> товар в ВК', 'vkshop-for-edd' ); ?>
		</p>
	</div>
	<?php
}

add_action( 'post_submitbox_misc_actions', 'vks_exclude_product_vk' );

function saveExcludeProduct($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (isset($_POST['vks_exclude_product'])) {
        $download_exclude = sanitize_text_field($_POST['vks_exclude_product']);
        update_post_meta($post_id, 'vks_exclude_product', $download_exclude);
    } else {
        delete_post_meta($post_id, 'vks_exclude_product');
    }
}

add_action('save_post', saveExcludeProduct);