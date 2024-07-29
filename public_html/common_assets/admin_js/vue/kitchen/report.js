$(document).ready(function () {

    let settings = {
        selector: '#report_template_body',
        plugins: 'advlist autolink charmap code directionality hr image legacyoutput link lists nonbreaking pagebreak paste preview searchreplace table visualblocks visualchars',

        toolbar: 'undo redo | styleselect | fontsizeselect | bold italic underline | forecolor backcolor | alignleft aligncenter alignright alignjustify | numlist bullist | cpb |  link image hr | table | code |  charmap | outdent indent | ltr rtl',
        menubar: 'file edit view insert format tools table',
        contextmenu: "link image imagetools table spellchecker",

        table_toolbar: "tableprops  tablerowprops, tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol | tabledelete",
        toolbar_sticky: true,
        toolbar_mode: 'sliding',

        content_style: 'facades_data:before{content:"---Блок фасадов---"} facades_data:after{content:"---Блок фасадов---"}' +
        'glass_data:before{content:"---Блок материалов витрин---"} glass_data:after{content:"---Блок материалов витрин---"}' +
        'corpus_data:before{content:"---Блок материалов корпуса---"} corpus_data:after{content:"---Блок материалов корпуса---"}' +
        'cokol_data:before{content:"---Блок материалов цоколя---"} cokol_data:after{content:"---Блок материалов цоколя---"}' +
        'tabletop_data:before{content:"---Блок материалов столшеницы---"} tabletop_data:after{content:"---Блок материалов столшеницы---"}' +
        'wallpanel_data:before{content:"---Блок материалов фартука---"} wallpanel_data:after{content:"---Блок материалов фартука---"}' +
        'modules_data:before{content:"---Блок модулей---"} modules_data:after{content:"---Блок модулей---"}' +
        'accessories_data:before{content:"---Блок комплектующих и фурнитуры---"} accessories_data:after{content:"---Блок комплектующих и фурнитуры---"}' +
        'comments_data:before{content:"---Блок комментариев к модулям---"} comments_data:after{content:"---Блок комментариев к модулям---"}',

        language : 'ru',
        paste_data_images: true,
        paste_as_text: true,
        image_advtab: true,
        relative_urls : false,
        remove_script_host: false,
        min_height: 400,

        // force_p_newlines : false,
        // force_br_newlines : true,
        // convert_newlines_to_brs : false,
        // remove_linebreaks : false,
        // forced_root_block : false,

        file_picker_types: 'image',
        custom_elements: 'pagebreak,facades_data,glass_data,corpus_data,cokol_data,tabletop_data,wallpanel_data,handles_data,modules_data,accessories_data,comments_data',
        extended_valid_elements: 'pagebreak[orientation|margin-header|margin-top|margin-left|margin-right|margin-bottom|margin-footer|sheet-size],facades_data,glass_data,corpus_data,cokol_data,tabletop_data,wallpanel_data,handles_data,modules_data,accessories_data,comments_data',
        visualblocks_default_state: false,
        // pagebreak_split_block: true,
        images_dataimg_filter: function(img) {
            return img.hasAttribute('internal-blob');
        },

        file_picker_callback: function (cb, value, meta) {
            let input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');

            input.onchange = function () {
                let file = this.files[0];

                let reader = new FileReader();
                reader.onload = function () {

                    if(file.size > 102400 * 1.5){
                        alert(document.getElementById('email_lang_file_too_big_kb').innerText)
                    } else {

                        let img = new Image();

                        img.src = reader.result;

                        img.onload = function() {

                            if(img.height > 800 || img.width > 800){
                                alert(document.getElementById('email_lang_file_too_big').innerText)
                            } else {
                                cb(reader.result, { title: file.name });
                            }
                        }
                    }

                };
                reader.readAsDataURL(file);
            };

            input.click();
        },

        setup: function (editor) {

            // editor.ui.registry.addButton('cpb', {
            //     text: 'Добавить страницу',
            //     onAction: function (_) {
            //         editor.windowManager.open(new_page_dialog)
            //
            //     }
            // });

            editor.ui.registry.addMenuButton('cpb', {
                text: 'Переменные',
                icon: 'code-sample',
                fetch: function (callback) {
                    var items = [
                        {
                            type: 'menuitem',
                            text: 'Новый лист',
                            onAction: function () {

                                new_page_dialog.initialData = {
                                    orientation: 'P',
                                    margin_header: $('#report_margin_header').val(),
                                    margin_top: $('#report_margin_top').val(),
                                    margin_left: $('#report_margin_left').val(),
                                    margin_right: $('#report_margin_right').val(),
                                    margin_bottom: $('#report_margin_bottom').val(),
                                    margin_footer: $('#report_margin_footer').val()
                                }

                                editor.windowManager.open(new_page_dialog)
                            }
                        },
                        {
                            type: 'nestedmenuitem',
                            text: 'Данные клиента',
                            getSubmenuItems: function () {

                                if(document.getElementById('settings_use_custom_form').value == 1){

                                    let arr = [];

                                    let cfd = JSON.parse(document.getElementById('settings_custom_form_data').innerText);

                                    for (let i = cfd.length; i--;){
                                        if(cfd[i].type != 'file'){
                                            arr.push({
                                                type: 'menuitem',
                                                text: cfd[i].label,
                                                onAction: function (_) {
                                                    editor.insertContent('{{client_' + cfd[i].id +'}}');
                                                }
                                            })
                                        }
                                    }

                                    return arr;


                                } else {
                                    return [
                                        {
                                            type: 'menuitem',
                                            text: 'Имя',
                                            onAction: function () {
                                                editor.insertContent('{{client_name}}');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Email',
                                            onAction: function () {
                                                editor.insertContent('{{client_email}}');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Телефон',
                                            onAction: function () {
                                                editor.insertContent('{{client_phone}}');
                                            }
                                        },
                                        {
                                            type: 'menuitem',
                                            text: 'Комментарии',
                                            onAction: function () {
                                                editor.insertContent('{{client_comments}}');
                                            }
                                        }
                                    ];
                                }
                            }
                        },
                        {
                            type: 'nestedmenuitem',
                            text: 'Фасады',
                            getSubmenuItems: function () {
                                return [
                                    {
                                        type: 'menuitem',
                                        text: 'Блок фасадов',
                                        onAction: function () {
                                            editor.insertContent('<facades_data><p></p></facades_data><p></p>');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: '------------------',
                                        onAction: function () {

                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Категория фрезеровки',
                                        onAction: function () {
                                            editor.insertContent('{{facade_set_category}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Подкатегория фрезеровки',
                                        onAction: function () {
                                            editor.insertContent('{{facade_set_subcategory}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Название фрезеровки',
                                        onAction: function () {
                                            editor.insertContent('{{facade_set_name}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Тип фрезеровки',
                                        onAction: function () {
                                            editor.insertContent('{{facade_set_type}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: '------------------',
                                        onAction: function () {

                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Категория декора',
                                        onAction: function () {
                                            editor.insertContent('{{facade_material_category}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Подкатегория декора',
                                        onAction: function () {
                                            editor.insertContent('{{facade_material_subcategory}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Название декора',
                                        onAction: function () {
                                            editor.insertContent('{{facade_material_name}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Артикул декора',
                                        onAction: function () {
                                            editor.insertContent('{{facade_material_code}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Количество декора (м2)',
                                        onAction: function () {
                                            editor.insertContent('{{facade_material_square}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Количество декора (п.м.)',
                                        onAction: function () {
                                            editor.insertContent('{{facade_material_flat}}');
                                        }
                                    },
                                ];
                            }
                        },
                        {
                            type: 'nestedmenuitem',
                            text: 'Витрины',
                            getSubmenuItems: function () {
                                return [
                                    {
                                        type: 'menuitem',
                                        text: 'Блок материалов витрин',
                                        onAction: function () {
                                            editor.insertContent('<glass_data><p></p></glass_data><p></p>');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: '------------------',
                                        onAction: function () {

                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Категория материала витрин',
                                        onAction: function () {
                                            editor.insertContent('{{glass_category}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Подкатегория материала витрин',
                                        onAction: function () {
                                            editor.insertContent('{{glass_subcategory}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Название материала витрин',
                                        onAction: function () {
                                            editor.insertContent('{{glass_name}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Артикул материала витрин',
                                        onAction: function () {
                                            editor.insertContent('{{glass_code}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Количество материала витрин (кв.м.)',
                                        onAction: function () {
                                            editor.insertContent('{{glass_square}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Количество материала витрин (п.м.)',
                                        onAction: function () {
                                            editor.insertContent('{{glass_flat}}');
                                        }
                                    }
                                ];
                            }
                        },
                        {
                            type: 'nestedmenuitem',
                            text: 'Корпус',
                            getSubmenuItems: function () {
                                return [
                                    {
                                        type: 'menuitem',
                                        text: 'Блок материалов корпуса',
                                        onAction: function () {
                                            editor.insertContent('<corpus_data><p></p></corpus_data><p></p>');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: '------------------',
                                        onAction: function () {

                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Категория материала корпуса',
                                        onAction: function () {
                                            editor.insertContent('{{corpus_category}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Подкатегория материала корпуса',
                                        onAction: function () {
                                            editor.insertContent('{{corpus_subcategory}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Название материала корпуса',
                                        onAction: function () {
                                            editor.insertContent('{{corpus_name}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Артикул материала корпуса',
                                        onAction: function () {
                                            editor.insertContent('{{corpus_code}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Количество материала корпуса (кв.м.)',
                                        onAction: function () {
                                            editor.insertContent('{{corpus_square}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Количество материала корпуса (п.м.)',
                                        onAction: function () {
                                            editor.insertContent('{{corpus_flat}}');
                                        }
                                    }
                                ];
                            }
                        },
                        {
                            type: 'nestedmenuitem',
                            text: 'Цоколь',
                            getSubmenuItems: function () {
                                return [
                                    {
                                        type: 'menuitem',
                                        text: 'Блок материалов цоколя',
                                        onAction: function () {
                                            editor.insertContent('<cokol_data><p></p></cokol_data><p></p>');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: '------------------',
                                        onAction: function () {

                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Категория материала цоколя',
                                        onAction: function () {
                                            editor.insertContent('{{cokol_category}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Подкатегория материала цоколя',
                                        onAction: function () {
                                            editor.insertContent('{{cokol_subcategory}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Название материала цоколя',
                                        onAction: function () {
                                            editor.insertContent('{{cokol_name}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Артикул материала цоколя',
                                        onAction: function () {
                                            editor.insertContent('{{cokol_code}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Количество материала цоколя (кв.м.)',
                                        onAction: function () {
                                            editor.insertContent('{{cokol_square}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Количество материала цоколя (п.м.)',
                                        onAction: function () {
                                            editor.insertContent('{{cokol_flat}}');
                                        }
                                    }
                                ];
                            }
                        },
                        {
                            type: 'nestedmenuitem',
                            text: 'Столешница',
                            getSubmenuItems: function () {
                                return [
                                    {
                                        type: 'menuitem',
                                        text: 'Блок материалов столешницы',
                                        onAction: function () {
                                            editor.insertContent('<tabletop_data><p></p></tabletop_data><p></p>');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: '------------------',
                                        onAction: function () {

                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Категория материала столешницы',
                                        onAction: function () {
                                            editor.insertContent('{{tabletop_category}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Подкатегория материала столешницы',
                                        onAction: function () {
                                            editor.insertContent('{{tabletop_subcategory}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Название материала столешницы',
                                        onAction: function () {
                                            editor.insertContent('{{tabletop_name}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Артикул материала столешницы',
                                        onAction: function () {
                                            editor.insertContent('{{tabletop_code}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Количество материала столешницы (кв.м.)',
                                        onAction: function () {
                                            editor.insertContent('{{tabletop_square}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Количество материала столешницы (п.м.)',
                                        onAction: function () {
                                            editor.insertContent('{{tabletop_flat}}');
                                        }
                                    }
                                ];
                            }
                        },
                        {
                            type: 'nestedmenuitem',
                            text: 'Фартук',
                            getSubmenuItems: function () {
                                return [
                                    {
                                        type: 'menuitem',
                                        text: 'Блок материалов фартука',
                                        onAction: function () {
                                            editor.insertContent('<wallpanel_data><p></p></wallpanel_data><p></p>');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: '------------------',
                                        onAction: function () {

                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Категория материала фартука',
                                        onAction: function () {
                                            editor.insertContent('{{wallpanel_category}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Подкатегория материала фартука',
                                        onAction: function () {
                                            editor.insertContent('{{wallpanel_subcategory}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Название материала фартука',
                                        onAction: function () {
                                            editor.insertContent('{{wallpanel_name}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Артикул материала фартука',
                                        onAction: function () {
                                            editor.insertContent('{{wallpanel_code}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Количество материала фартука (кв.м.)',
                                        onAction: function () {
                                            editor.insertContent('{{wallpanel_square}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Количество материала фартука (п.м.)',
                                        onAction: function () {
                                            editor.insertContent('{{wallpanel_flat}}');
                                        }
                                    }
                                ];
                            }
                        },
                        {
                            type: 'nestedmenuitem',
                            text: 'Фартук',
                            getSubmenuItems: function () {
                                return [
                                    {
                                        type: 'menuitem',
                                        text: 'Блок материалов фартука',
                                        onAction: function () {
                                            editor.insertContent('<wallpanel_data><p></p></wallpanel_data><p></p>');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: '------------------',
                                        onAction: function () {

                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Категория материала фартука',
                                        onAction: function () {
                                            editor.insertContent('{{wallpanel_category}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Подкатегория материала фартука',
                                        onAction: function () {
                                            editor.insertContent('{{wallpanel_subcategory}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Название материала фартука',
                                        onAction: function () {
                                            editor.insertContent('{{wallpanel_name}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Артикул материала фартука',
                                        onAction: function () {
                                            editor.insertContent('{{wallpanel_code}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Количество материала фартука (кв.м.)',
                                        onAction: function () {
                                            editor.insertContent('{{wallpanel_square}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Количество материала фартука (п.м.)',
                                        onAction: function () {
                                            editor.insertContent('{{wallpanel_flat}}');
                                        }
                                    }
                                ];
                            }
                        },
                        {
                            type: 'nestedmenuitem',
                            text: 'Модули',
                            getSubmenuItems: function () {
                                return [
                                    {
                                        type: 'menuitem',
                                        text: 'Блок модулей',
                                        onAction: function () {
                                            editor.insertContent('<modules_data><p></p></modules_data><p></p>');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: '------------------',
                                        onAction: function () {

                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Номер модуля на изображениях',
                                        onAction: function () {
                                            editor.insertContent('{{module_number}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Название',
                                        onAction: function () {
                                            editor.insertContent('{{module_name}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Артикул',
                                        onAction: function () {
                                            editor.insertContent('{{module_code}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Ширина',
                                        onAction: function () {
                                            editor.insertContent('{{module_width}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Высота',
                                        onAction: function () {
                                            editor.insertContent('{{module_height}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Глубина',
                                        onAction: function () {
                                            editor.insertContent('{{module_depth}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Комментарии',
                                        onAction: function () {
                                            editor.insertContent('{{module_comments}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Цена',
                                        onAction: function () {
                                            editor.insertContent('{{module_price}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Итоговая сумма',
                                        onAction: function () {
                                            editor.insertContent('{{total_modules_price}}');
                                        }
                                    },
                                ];
                            }
                        },
                        {
                            type: 'nestedmenuitem',
                            text: 'Комплектующие и фурнитура',
                            getSubmenuItems: function () {
                                return [
                                    {
                                        type: 'menuitem',
                                        text: 'Блок комплектующих и фурнитуры',
                                        onAction: function () {
                                            editor.insertContent('<accessories_data><p></p></accessories_data><p></p>');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: '------------------',
                                        onAction: function () {

                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Название',
                                        onAction: function () {
                                            editor.insertContent('{{accessories_name}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Артикул',
                                        onAction: function () {
                                            editor.insertContent('{{accessories_code}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Количество',
                                        onAction: function () {
                                            editor.insertContent('{{accessories_count}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Цена за шт.',
                                        onAction: function () {
                                            editor.insertContent('{{accessories_price_per_piece}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Цена',
                                        onAction: function () {
                                            editor.insertContent('{{accessories_price}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Итоговая сумма',
                                        onAction: function () {
                                            editor.insertContent('{{accessories_total_price}}');
                                        }
                                    },
                                ];
                            }
                        },
                        {
                            type: 'menuitem',
                            text: 'Итоговая сумма проекта',
                            onAction: function () {
                                editor.insertContent('{{project_total_price}}');
                            }
                        },
                        {
                            type: 'nestedmenuitem',
                            text: 'Прочее',
                            getSubmenuItems: function () {
                                return [
                                    {
                                        type: 'menuitem',
                                        text: 'Текущая страница',
                                        onAction: function () {
                                            editor.insertContent('{PAGENO}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Общее количество страниц',
                                        onAction: function () {
                                            editor.insertContent('{nb}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'День',
                                        onAction: function () {
                                            editor.insertContent('{{date_day}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Месяц',
                                        onAction: function () {
                                            editor.insertContent('{{date_month}}');
                                        }
                                    },
                                    {
                                        type: 'menuitem',
                                        text: 'Год',
                                        onAction: function () {
                                            editor.insertContent('{{date_year}}');
                                        }
                                    }
                                ];
                            }
                        },
                    ];
                    callback(items);
                }
            });

        },

    };

    let settings_heading = Object.assign({}, settings);
    settings_heading.selector = '#report_template_header';
    let settings_footer = Object.assign({}, settings);
    settings_footer.selector = '#report_template_footer';



    let new_page_dialog =  {
        title: 'Добавить лист',
        body: {
            type: 'panel',
            items: [
                // {
                //     type: 'input',
                //     name: 'catdata',
                //     label: 'enter the name of a cat'
                // },
                // {
                //     type: 'checkbox',
                //     name: 'isdog',
                //     label: 'tick if cat is actually a dog'
                // },
                {
                    type: 'selectbox',
                    name: 'orientation',
                    label: 'Ориентация',
                    size: 1,
                    items: [
                        { value: 'P', text: 'Книжная' },
                        { value: 'L', text: 'Альбомная' }
                    ]
                },
                {
                    type: 'input',
                    name: 'margin_header',
                    label: 'Отступ верхнего колонтитула от верхнего края (мм)',
                },
                {
                    type: 'input',
                    name: 'margin_top',
                    label: 'Отступ содержимого от верхнего края (мм)',
                },
                {
                    type: 'input',
                    name: 'margin_left',
                    label: 'Отступ содержимого от левого края (мм)',
                },
                {
                    type: 'input',
                    name: 'margin_right',
                    label: 'Отступ содержимого от правого края (мм)',
                },
                {
                    type: 'input',
                    name: 'margin_bottom',
                    label: 'Отступ содержимого от нижнего края (мм)',
                },
                {
                    type: 'input',
                    name: 'margin_footer',
                    label: 'Отступ нижнего колонтитула от нижнего края (мм)',
                },
                // {
                //     type: 'sizeinput', // component type
                //     name: 'size', // identifier
                //     label: 'Dimensions',
                // }
            ]
        },
        buttons: [
            {
                type: 'cancel',
                name: 'closeButton',
                text: 'Cancel'
            },
            {
                type: 'submit',
                name: 'submitButton',
                text: 'Добавить',
                primary: true
            }
        ],
        initialData: {
            // catdata: 'initial Cat',
            // isdog: false,

            // size: {
            //     width: '210',
            //     height: '297'
            // }
        },
        onSubmit: function (api) {
            var data = api.getData();

            let ori = data.orientation == 'L' ? 'Альбомная' : 'Книжная'

            // tinymce.activeEditor.execCommand('mceInsertContent', false, '<pagebreak sheet-size="'+ data.size.width + 'mm ' + data.size.height +'mm"></pagebreak><p class="params_p">НОВЫЙ ЛИСТ</p><p></p>');
            tinymce.activeEditor.execCommand('mceInsertContent', false, '<pagebreak orientation="'+ data.orientation +'" margin-header="'+ data.margin_header +'mm" margin-top="'+ data.margin_top+'mm" margin-left="'+ data.margin_left +'mm" margin-right="'+ data.margin_right+'mm" margin-bottom="'+data.margin_bottom+'mm" margin-footer="'+data.margin_footer+'mm"></pagebreak><p class="params_p">{{НОВЫЙ ЛИСТ   ОРИЕНТАЦИЯ: '+ ori +'}}</p><p></p>');
            // tinymce.activeEditor.execCommand('mceInsertContent', false, '<p>My ' + pet +'\'s name is: <strong>' + data.catdata + '</strong></p>');
            api.close();
        }
    };

    tinymce.init(settings);
    tinymce.init(settings_heading);
    tinymce.init(settings_footer);




    $('#testpdfinner').click(function (e) {
        e.preventDefault();
        console.log(tinymce.editors['report_template_body'].getContent())

        $('#presave').html(tinymce.editors['report_template_body'].getContent())

        $('#presave').find('.pagebreak').each(function (e) {
            $(this).replaceWith('<pagebreak margin-top="'+ $(this).attr('data-mt') +'"></pagebreak>')
        });


        console.log($('#presave').html())

    });

    function convert_html(content) {
        let presave = $('#presave');
        presave.html(content);
        presave.find('.params_p').remove();

        let result = presave.html();
        presave.empty();

        return result;
    }

    function convert_html2(content) {
        let presave = $('#presave');
        presave.html(content);
        presave.find('.pagebreak').each(function (e) {
            $(this).replaceWith('<pagebreak margin-top="'+ $(this).attr('data-mt') +'"></pagebreak>')
        });

        let result = presave.html();
        presave.empty();

        return result;
    }

    $('#testpdf').click(function () {
        // console.log(tinymce.editors['report_template_body'].getContent());

        let scope = $(this);

        let data = new FormData();

        data.append('body', convert_html(tinymce.editors['report_template_body'].getContent()));
        data.append('header', convert_html(tinymce.editors['report_template_header'].getContent()));
        data.append('footer', convert_html(tinymce.editors['report_template_footer'].getContent()));

        data.append('settings', JSON.stringify({
            report_page_orientation: $('#report_page_orientation').val(),
            report_margin_header: $('#report_margin_header').val(),
            report_margin_top: $('#report_margin_top').val(),
            report_margin_left: $('#report_margin_left').val(),
            report_margin_right: $('#report_margin_right').val(),
            report_margin_bottom: $('#report_margin_bottom').val(),
            report_margin_footer: $('#report_margin_footer').val(),
            custom_report_common_view: $('input[name="custom_report_common_view"]').prop('checked'),
            custom_report_edges_view: $('input[name="custom_report_edges_view"]').prop('checked'),
            custom_report_comms_only_view: $('input[name="custom_report_comms_only_view"]').prop('checked'),
            custom_report_top_view: $('input[name="custom_report_top_view"]').prop('checked'),
            custom_report_walls_view: $('input[name="custom_report_walls_view"]').prop('checked'),
            custom_report_show_logo: $('input[name="custom_report_show_logo"]').prop('checked')
        }));


        let xhr = new XMLHttpRequest();
        xhr.open("POST",  scope.attr('data-action'));
        xhr.responseType = "string";
        xhr.send(data);
        xhr.addEventListener("readystatechange", function () {
            if (this.readyState === 4) {

                let response = this.response;
                // let blob = new Blob([response], {type: "application/pdf;charset=utf-8"});

                console.log(response)

                open_data_uri_window('data:application/pdf;charset=utf-8;base64,' + response);


                //
                // var reader = new FileReader();
                // reader.readAsDataURL(blob);
                // reader.onloadend = function() {
                //     var base64data = reader.result;
                //     console.log(reader);
                //
                //     open_data_uri_window(base64data);
                //
                // }

                // let url = window.URL.createObjectURL(blob);
                // console.log(blob)
                // console.log(url)
                // var link = document.createElement("a");
                // link.download = "data.pdf";
                // document.body.appendChild(link);
                // link.href =  window.URL.createObjectURL(blob);
                // link.click();

            }
        });

        // $.ajax({
        //     url: scope.attr('data-action'),
        //     type: 'post',
        //     // data: {data: convert_html2(tinymce.editors['email_template'].getContent())}
        //     data: {
        //         body: convert_html(tinymce.editors['report_template_body'].getContent()),
        //         header: convert_html(tinymce.editors['report_template_header'].getContent()),
        //         footer: convert_html(tinymce.editors['report_template_footer'].getContent())
        //     }
        // }).done(function(data) {
        //     // window.open('/filename.pdf')
        //     console.log(data)
        // });

    })


    $('#sub_form').submit(function (e) {
        e.preventDefault();

        let inputs = $('.common_checkboxes').find('input[type="checkbox"]');
        let data = {};



        for (let i = 0; i < inputs.length; i++){
            data[inputs[i].name] = inputs[i].checked === true ? 1 : 0
        }




        data.report_additional_text = $('textarea[name="report_additional_text"]').val();
        //
        // data.report_use_custom = $('input[name="report_use_custom"]')[0].checked === true ? 1 : 0;
        //
        // data.report_template_header = tinymce.editors['report_template_header'].getContent();
        // data.report_template_footer = tinymce.editors['report_template_footer'].getContent();
        // data.report_template_body = tinymce.editors['report_template_body'].getContent();
        //
        // data.report_custom_settings = JSON.stringify({
        //     report_page_orientation: $('#report_page_orientation').val(),
        //     report_margin_header: $('#report_margin_header').val(),
        //     report_margin_top: $('#report_margin_top').val(),
        //     report_margin_left: $('#report_margin_left').val(),
        //     report_margin_right: $('#report_margin_right').val(),
        //     report_margin_bottom: $('#report_margin_bottom').val(),
        //     report_margin_footer: $('#report_margin_footer').val(),
        //     custom_report_common_view: $('input[name="custom_report_common_view"]').prop('checked'),
        //     custom_report_edges_view: $('input[name="custom_report_edges_view"]').prop('checked'),
        //     custom_report_comms_only_view: $('input[name="custom_report_comms_only_view"]').prop('checked'),
        //     custom_report_top_view: $('input[name="custom_report_top_view"]').prop('checked'),
        //     custom_report_walls_view: $('input[name="custom_report_walls_view"]').prop('checked'),
        //     custom_report_show_logo: $('input[name="custom_report_show_logo"]').prop('checked')
        // });


        $.ajax({
            url: $('#sub_form').attr('action'),
            type: 'post',
            data: {data: data}
        }).done(function(data) {
            console.log(data);
            toastr.success(document.getElementById('success_message').innerHTML)

        });

    })
})

function open_data_uri_window(url) {

    var url_with_name = url.replace("data:application/pdf;", "data:application/pdf;name=myname.pdf;")


    var html = '<html>' +
        '<style>html, body { padding: 0; margin: 0; } iframe { width: 100%; height: 100%; border: 0;}  </style>' +
        '<body>' +
        '<iframe type="application/pdf" src="' + url_with_name + '"></iframe>' +
        '</body></html>';
    var a = window.open("about:blank", "_blank");
    a.document.write(html);
    a.document.close();
}