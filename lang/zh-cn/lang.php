<?php return [
    'plugin' => [
        'name' => 'training',
        'description' => '培训管理'
    ],
    'access'=> [
        'orders'=>'管理订单',
        'products'=>'管理产品',
        'categories'=>'管理类别',
        'other_products'=>'管理其他用户的产品',
        'tab'=>'OctoCart',
        'import_export'=>'允许导入和导出',
    ],
    'product'=> [
        'name'=>'产品',
        'description'=>'显示单个产品。',
        'slug'=>'产品子弹',
        'slug_description'=>'使用提供的slug值查找产品。',
        'title'=>'标题',
        'categories'=>'类别',
        'published'=>'已发布',
        'created_at'=>'创建日期',
        'updated_at'=>'最新更新',
        'title_placeholder'=>'新产品标题',
        'slug_placeholder'=>'新产品弹头',
        'tab_edit'=>'常规',
        'tab_categories'=>'类别',
        'tab_attributes'=>'属性',
        'categories_comment'=>'选择产品所属的类别',
        'categories_placeholder'=>'没有类别,您应该首先创建一个类别！',
        'excerpt'=>'产品简短说明',
        'images'=>'图像',
        'price'=>'价格',
        'promote'=>'晋升为首页',
        'active'=>'活动',
        'type'=>'Type',
        'simple'=>'简单产品',
        'variable'=>'变量产品',
        'tab_inventory'=>'库存',
        'tab_shipping'=>'运费',
        'tab_linked_products'=>'链接的产品',
        'sale_price'=>'销售价格',
        'sku'=>'SKU',
        'sku_description'=>'SKU是指库存单位,是可以购买的每种不同产品和服务的唯一标识符。',
        'tab_manage'=>'管理',
        'manage_stock'=>'管理库存',
        'manage_stock_description'=>'在产品级别启用库存管理',
        'quantity'=>'库存数量',
        'quantity_description'=>'库存数量。如果这是可变产品,那么除非您在变化级别上定义库存,否则将使用该值来控制所有变化的库存。',
        'backorders'=>'是否允许延期交货？',
        'backorders_description'=>'如果管理库存,则控制是否允许补货。如果启用,则库存数量可以低于0。',
        'backorders_no'=>'不允许',
        'backorders_notify'=>'允许,但通知客户',
        'backorders_yes'=>'允许',
        'status'=>'库存状态',
        'status_description'=>'控制该产品在前端是否被列为“有货”或“无货”。',
        'instock'=>'库存',
        'outofstock'=>'缺货',
        'sold_individually'=>'单独出售',
        'sold_individually_description'=>'启用此选项后,仅允许以单笔订单购买此商品之一,',
        'weight'=>'重量',
        'weight_description'=>'十进制重量',
        'dimensions'=>'Dimensions',
        'dimensions_description'=>'十进制形式的LxWxH',
        'length'=>'长度',
        'width'=>'宽度',
        'height'=>'Height',
        'linked_products'=>'链接产品',
        'up_sells'=>'追加销售',
        'up_sells_description'=>'追加销售是您推荐的产品,而不是当前浏览的产品,例如,利润更高或更优质或更昂贵的产品。',
        'cross_sells'=>'交叉销售',
        'cross_sells_description'=>'交叉销售是您根据当前产品在购物车中促销的产品。',
        'published_on'=>'发布于',
        'published_on_description'=>'将此字段留空以使用当前日期。',
        'tab_variations'=>'Variations',
        'add_variation'=>'添加变化',
        'product_variation'=>'Variation',
        'content'=>'描述',
        'author_email'=>'作者电子邮件'
    ],
    'attribute'=> [
        'name'=>'名称',
        'value'=>'值',
        'id'=>'ID',
        'product_id'=>'培训项目ID',
        'created_at'=>'创建日期',
        'updated_at'=>'最新更新',
        'code'=>'代码',
    ],
    'attributes'=> [
        'list_title'=>'属性',
        'import'=>'导入属性',
        'export'=>'导出属性',
        'menu_label'=>'属性',
    ],
    'products'=> [
        'menu_label'=>'产品',
        'name'=>'产品',
        'description'=>'在页面上显示产品列表。',
        'pagination'=>'页码',
        'pagination_description'=>'此值用于确定用户所在的页面。',
        'filter'=>'类别过滤器',
        'filter_description'=>'输入类别标签或URL参数以过滤产品。留空以显示所有产品。',
        'products_per_page'=>'每页产品',
        'products_per_page_validation'=>'每页商品的格式无效,',
        'no_products'=>'没有产品消息',
        'no_products_description'=>'如果没有产品,显示在产品列表中的消息。',
        'order'=>'产品订单',
        'order_description'=>'订购产品的属性',
        'chart_published'=>'已发布',
        'chart_drafts'=>'草稿',
        'chart_total'=>'总计',
        'title'=>'产品',
        'creating'=>'正在创建产品...',
        'cancel'=>'取消',
        'return'=>'返回产品列表',
        'saving'=>'正在保存产品...',
        'deleting'=>'正在删除产品...',
        'deleting_confirm'=>'您真的要删除此产品吗？',
        'delete_confirm'=>'您真的要删除此产品吗？',
        'new_product'=>'新产品',
        //'delete_confirm'=>'确定吗？',
        'show_confirm'=>'确定吗？',
        'hide_confirm'=>'确定吗？',
        'show_selected'=>'显示所选内容',
        'hide_selected'=>'隐藏所选内容',
        'list_title'=>'管理产品',
        'product'=>'产品',
        'edit_product'=>'编辑产品',
        'create_product'=>'创建产品',
        'import'=>'进口',
        'export'=>'出口',
        'delete_all'=>'全部删除',
        'actions'=>'动作',
    ],
    'order'=> [
        'menu_label'=>'订单',
        'name'=>'订单',
        'description'=>'显示单个订单。',
        'order_id'=>'订单ID',
        'order_id_description'=>'使用提供的ID值查找订单。',
        'order'=>'订单',
        'details'=>'详细信息',
        'create_order'=>'订单',
        'general_details'=>'常规详细信息',
        'created_at'=>'订购日期：',
        'status'=>'订单状态：',
        'customer'=>'客户：',
        'billing_details'=>'帐单明细',
        'shipping_details'=>'运输明细',
        'items'=>'报名信息',
        'note'=>'添加备注：',
        'email'=>'电子邮件地址：',
        'phone'=>'电话：',
        'billing_details_name'=>'发票详情名称',
        'billing_details_value'=>'发票详情值',
        'shipping_info_name'=>'取领证信息值',
        'id'=>'ID',
        'shipping_method'=>'运领证方式',
        'shipping_total'=>'运费',
        'payment_method'=>'付款方式',
        'date_completed'=>'完成日期：',
        'date_paid'=>'支付日期：',
    ],
    'status'=> [
        'pending'=>'待付款',
        'processing'=>'处理中',
        'on-hold'=>'挂起',
        'paid'=>'已付费',
        'completed'=>'交易完成',
        'cancelled'=>'订单已取消',
        'refunded'=>'已退款',
        'failed'=>'支付失败',
    ],
    'orders'=> [
        'menu_label'=>'订单列表',
        'list_title'=>'订单列表',
        'name'=>'订单',
        'description'=>'所有订单列表。',
        'no_orders'=>'没有任何订单',
        'no_orders_description'=>'如果没有订单,在订单列表中显示的消息。',
        'user'=>'用户',
        'email'=>'电子邮件',
        'items'=>'报名信息',
        'billing_info'=>'发票信息',
        'shipping_info'=>'领证信息',
        'delete_confirm'=>'确定要删除吗？',
        'created_at'=>'创建日期',
        'vat'=>'税额',
        'total'=>'总计',
        'currency'=>'货币',
        'order'=>'订单',
        'status'=>'状态',
        'actions'=>'动作',
        'edit'=>'Edit',
        'delete'=>'删除',
        'confirm'=>'确定吗？',
        'complete'=>'变更为已完成',
        'view'=>'查看详情',
        'title'=>'订单管理',
    ],
    'category'=> [
        'title'=>'标题',
        'products'=>'产品',
        'title_placeholder'=>'新类别标题',
        'slug'=>'子弹',
        'slug_placeholder'=>'new-category-slug',
        'image'=>'图像',
        'description'=>'Description',
        'active'=>'活动',
        'external_id'=>'外部ID',
        'excerpt'=>'摘录',
        'tab_edit'=>'常规',
        'tab_manage'=>'管理'
    ],
    'categories'=> [
        'menu_label'=>'类别',
        'new_category'=>'新类别',
        'delete_confirm'=>'确定吗？',
        'reorder'=>'重新排序类别',
        'list_title'=>'管理类别',
        'return'=>'返回类别列表',
        'category'=>'类别',
        'edit_category'=>'编辑类别',
        'create_category'=>'创建类别',
        'title'=>'类别',
        'creating'=>'正在创建类别...',
        'saving'=>'保存类别...',
        'deleting'=>'正在删除类别...',
        'cancel'=>'取消',
        'deleting_confirm'=>'您真的要删除此类别吗？',
        'delete_confirm'=>'您真的要删除此类别吗？',
        'name'=>'类别列表',
        'description'=>'在页面上显示类别列表。',
        'category_slug'=>'类别子弹头',
        'category_slug_description'=>'使用提供的子弹值查找类别。默认组件的一部分使用此属性来标记当前活动的类别。',
        'category_display_empty'=>'显示空类别',
        'category_display_empty_description'=>'显示没有任何产品的类别。',
        'actions'=>'动作',
    ],
    'cart'=> [
        'name'=>'购物车',
        'description'=>'显示用户购物车的内容并对其进行处理。',
        'no_products'=>'没有产品消息',
        'no_products_description'=>'如果没有产品,显示在产品列表中的消息。',
    ],
    'checkout'=> [
        'name'=>'结帐',
        'description'=>'在页面上显示账单和运输表格。',
    ],
    'settings'=> [
        'menu_label'=>'设置',
        'menu_description'=>'管理OctoCart配置',
        'product_display_page'=>'产品页面',
        'product_display_page_description'=>'产品展示页面文件的名称。',
        'category_page'=>'类别页面',
        'category_page_description'=>'类别链接的类别页面文件的名称。',
        'cart_page'=>'购物车页面',
        'cart_page_description'=>'购物车页面文件的名称。',
        'checkout_page'=>'结帐页面',
        'checkout_page_description'=>'结帐页面文件的名称。',
        'success_page'=>'成功页面',
        'success_page_description'=>'成功页面文件的名称。',
        'order_display_page'=>'订单页面',
        'order_display_page_description'=>'订单页面文件的名称。',
        'section_currency'=>'货币',
        'section_currency_description'=>'以下选项影响价格在前端的显示方式。',
        'section_pages'=>'Pages',
        'section_pages_description'=>'请选择页面。该属性由组件的局部使用。',
        'section_vat'=>'增值税',
        'vat_state'=>'检查您是否要在结帐流程中对总金额应用增值税。',
        'vat_value'=>'增值税值',
        'vat_value_description'=>'请输入增值税值。',
        'section_redirect'=>'将商品添加到购物车后重定向用户',
        'redirect_user_after_add_to_cart'=>'添加到购物车重定向',
        'redirect_user_after_add_to_cart_description'=>'输入将商品添加到购物车时希望将客户重定向到的页面,或者输入不进行任何重定向。',
        'currency'=>'Currency',
        'currency_description'=>'请选择货币。',
        'tab_general'=>'常规',
        'tab_checkout'=>'结帐',
        'section_messages'=>'电子邮件',
        'section_messages_description'=>'在这里,您可以自定义在下订单后发送给站点管理员和客户的邮件。',
        'admin_emails'=>'管理员电子邮件',
        'admin_emails_description'=>'在每个下订单后,一封包含订单详细信息的电子邮件将被发送到上面列表中的所有地址。请每行添加一个电子邮件地址。',
        'send_user_message'=>'下订单后向客户发送电子邮件',
        'currency_pos'=>'货币头寸',
        'currency_pos_description'=>'这控制货币符号的位置。',
        'currency_pos_left'=>'左',
        'currency_pos_right'=>'右',
        'currency_pos_left_space'=>'左随空格',
        'currency_pos_right_space'=>'带空格的权利',
        'thousand_sep'=>'千位分隔符',
        'thousand_sep_description'=>'这设置显示价格的千位分隔符。',
        'decimal_sep'=>'十进制分隔符',
        'decimal_sep_description'=>'这将设置显示价格的小数点。',
        'num_decimals'=>'十进制数',
        'num_decimals_description'=>'这设置显示的价格中显示的小数位数。',
    ],
    'shipping'=> [
        'menu_label'=>'运费',
        'menu_description'=>'可用的取证方式。',
        'actions'=>'动作',
        'new_method'=>'新方法',
        'list_title'=>'发货方式',
        'title'=>'运费',
        'create_method'=>'创建方法',
        'edit_method'=>'编辑方法',
        'delete_confirm'=>'您真的要删除此取证方式吗？',
    ],
    'payments'=> [
        'menu_label'=>'付款方式',
        'menu_description'=>'可用的付款方式。',
        'actions'=>'动作',
        'new_method'=>'新方法',
        'list_title'=>'付款方式',
        'title'=>'付款方式',
        'create_method'=>'创建方法',
        'edit_method'=>'编辑方法',
        'delete_confirm'=>'您真的要删除此付款方式吗？',
    ],
    'shipping_method'=> [
        'id'=>'ID',
        'active'=>'启用',
        'name'=>'名称',
        'code'=>'代码',
        'price'=>'价格',
        'weight'=>'重量',
        'description'=>'描述',
        'created_at'=>'创建日期',
        'updated_at'=>'最新更新',
    ],
    'shipping_methods'=> [
        'free_shipping'=>'自取',
        'free_shipping_description'=>'学员按照通知到指定的地方去领证！',
    ]
];
