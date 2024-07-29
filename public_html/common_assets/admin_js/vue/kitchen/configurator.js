let options = {
    wall: [
        {
            key: 'spec',
            name: 'Основные параметры',
            children: [
                {
                    key: 'orientation',
                    name: 'Ориентация',
                    type: 'select',
                    values: [
                        {
                            label: 'Горизонтальная',
                            val: 'h'
                        },
                        {
                            label: 'Вертикальная',
                            val: 'v'
                        },
                        {
                            label: 'Фронтальная',
                            val: 'f'
                        }
                    ]
                },
                {
                    key: 'type',
                    name: 'Форма',
                    type: 'select',
                    values: []
                }
            ]
        },
        {
            key: 'material',
            name: 'Материал',
            children: [
                {
                    key: 'material',
                    name: '',
                    type: 'input_mat'
                },
                {
                    key: 'nmap',
                    name: '',
                    type: 'input_image'
                }
            ]
        },
        {
            key: 'size',
            name: 'Размеры',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pos',
            name: 'Позиция',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pivot',
            name: 'Точка отсчета',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'select',
                    values: [
                        {
                            label: 'Слева',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Справа',
                            val: 1
                        }
                    ],
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'select',
                    values: [
                        {
                            label: 'Снизу',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сверху',
                            val: 1
                        },
                    ],
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'select',
                    values: [
                        {
                            label: 'Спереди',
                            val: 1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сзади',
                            val: -1
                        },
                    ],
                }
            ]
        },
        {
            key: 'rot',
            name: 'Поворот',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'number'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'number'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'number'
                }
            ]
        },
        {
            key: 'condition',
            name: 'Условие',
            type: 'input_cond'
        },
    ],
    tabletop: [
        {
            key: 'spec',
            name: 'Основные параметры',
            children: [
                {
                    key: 'orientation',
                    name: 'Ориентация',
                    type: 'select',
                    values: [
                        {
                            label: 'Горизонтальная',
                            val: 'h'
                        },
                        {
                            label: 'Вертикальная',
                            val: 'v'
                        },
                        {
                            label: 'Фронтальная',
                            val: 'f'
                        }
                    ]
                },
                {
                    key: 'type',
                    name: 'Форма',
                    type: 'select',
                    values: []
                }
            ]
        },
        {
            key: 'material',
            name: 'Материал',
            children: [
                {
                    key: 'material',
                    name: '',
                    type: 'input_mat'
                }

            ]
        },
        {
            key: 'size',
            name: 'Размеры',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pos',
            name: 'Позиция',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pivot',
            name: 'Точка отсчета',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'select',
                    values: [
                        {
                            label: 'Слева',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Справа',
                            val: 1
                        }
                    ],
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'select',
                    values: [
                        {
                            label: 'Снизу',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сверху',
                            val: 1
                        },
                    ],
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'select',
                    values: [
                        {
                            label: 'Спереди',
                            val: 1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сзади',
                            val: -1
                        },
                    ],
                }
            ]
        },
        {
            key: 'rot',
            name: 'Поворот',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'number'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'number'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'number'
                }
            ]
        },
        {
            key: 'condition',
            name: 'Условие',
            type: 'input_cond'
        },
    ],
    section: [
        {
            key: 'spec',
            name: 'Основные параметры',
            children: [
                {
                    key: 'tags',
                    name: 'Идентификатор',
                    type: 'text'
                },
                {
                    key: 'drag_enabled',
                    name: 'Можно наполнять',
                    type: 'select',
                    values: [
                        {
                            label: 'Да',
                            val: true
                        },
                        {
                            label: 'Нет',
                            val: false
                        }
                    ]
                },
                // {
                //     key: 'div_enabled',
                //     name: 'Можно делить',
                //     type: 'select',
                //     values: [
                //         {
                //             label: 'Да',
                //             val: true
                //         },
                //         {
                //             label: 'Нет',
                //             val: false
                //         }
                //     ]
                // },
                {
                    key: 'ds_x',
                    name: 'Шаг (Ось X)',
                    type: 'number'
                },
                {
                    key: 'ds_y',
                    name: 'Шаг (Ось Y)',
                    type: 'number'
                },
                {
                    key: 'ds_z',
                    name: 'Шаг (Ось Z)',
                    type: 'number'
                },
                {
                    key: 'drag_single',
                    name: 'Заменять наполнение',
                    type: 'select',
                    values: [
                        {
                            label: 'Да',
                            val: true
                        },
                        {
                            label: 'Нет',
                            val: false
                        }
                    ]
                },
                {
                    key: 'drag_items',
                    name: 'Доступные элементы',
                    type: 'item_picker',
                    catalog: 'catalogue',
                    multiple: true
                },
                // {
                //     key: 'drag_hide',
                //     name: 'Скрывать при',
                //     type: 'item_picker',
                //     catalog: 'catalogue',
                //     multiple: true
                // }
            ]
        },
        {
            key: 'size',
            name: 'Размеры',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pos',
            name: 'Позиция',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pivot',
            name: 'Точка отсчета',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'select',
                    values: [
                        {
                            label: 'Слева',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Справа',
                            val: 1
                        }
                    ],
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'select',
                    values: [
                        {
                            label: 'Снизу',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сверху',
                            val: 1
                        },
                    ],
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'select',
                    values: [
                        {
                            label: 'Спереди',
                            val: 1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сзади',
                            val: -1
                        },
                    ],
                }
            ]
        },
        {
            key: 'rot',
            name: 'Поворот',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'condition',
            name: 'Условие',
            type: 'input_cond'
        },
    ],
    section_div: [
        {
            key: 'spec',
            name: 'Основные параметры',
            children: [
                {
                    key: 'tags',
                    name: 'Идентификатор',
                    type: 'text'
                },
                {
                    key: 'drag_enabled',
                    name: 'Можно наполнять',
                    type: 'select',
                    values: [
                        {
                            label: 'Да',
                            val: true
                        },
                        {
                            label: 'Нет',
                            val: false
                        }
                    ]
                },
                {
                    key: 'ds_x',
                    name: 'Шаг (Ось X)',
                    type: 'number'
                },
                {
                    key: 'ds_y',
                    name: 'Шаг (Ось Y)',
                    type: 'number'
                },
                {
                    key: 'ds_z',
                    name: 'Шаг (Ось Z)',
                    type: 'number'
                },
                {
                    key: 'drag_items',
                    name: 'Доступное наполнение',
                    type: 'item_picker',
                    catalog: 'catalogue',
                    multiple: true
                }
            ]
        },
        {
            key: 'size',
            name: 'Размеры',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pos',
            name: 'Позиция',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pivot',
            name: 'Точка отсчета',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'select',
                    values: [
                        {
                            label: 'Слева',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Справа',
                            val: 1
                        }
                    ],
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'select',
                    values: [
                        {
                            label: 'Снизу',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сверху',
                            val: 1
                        },
                    ],
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'select',
                    values: [
                        {
                            label: 'Спереди',
                            val: 1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сзади',
                            val: -1
                        },
                    ],
                }
            ]
        },
        {
            key: 'rot',
            name: 'Поворот',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'condition',
            name: 'Условие',
            type: 'input_cond'
        },
    ],
    divider: [
        {
            key: 'spec',
            name: 'Основные параметры',
            children: [
                {
                    key: 'tags',
                    name: 'Идентификатор',
                    type: 'text'
                },
                {
                    key: 'orientation',
                    name: 'Ориентация',
                    type: 'select',
                    values: [
                        {
                            label: 'Вертикальная',
                            val: 'v'
                        },
                        {
                            label: 'Горизонтальная',
                            val: 'h'
                        }
                    ]
                }
            ]
        },
        {
            key: 'size',
            name: 'Размеры',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pos',
            name: 'Позиция',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pivot',
            name: 'Точка отсчета',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'select',
                    values: [
                        {
                            label: 'Слева',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Справа',
                            val: 1
                        }
                    ],
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'select',
                    values: [
                        {
                            label: 'Снизу',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сверху',
                            val: 1
                        },
                    ],
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'select',
                    values: [
                        {
                            label: 'Спереди',
                            val: 1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сзади',
                            val: -1
                        },
                    ],
                }
            ]
        },
        {
            key: 'rot',
            name: 'Поворот',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'condition',
            name: 'Условие',
            type: 'input_cond'
        },
    ],
    door: [
        {
            key: 'spec',
            name: 'Основные параметры',
            children: [
                {
                    key: 'type',
                    name: 'Тип открывания',
                    type: 'select',
                    values: [
                        {
                            label: 'Справа',
                            val: 'rtl'
                        },
                        {
                            label: 'Слева',
                            val: 'ltr'
                        },
                        {
                            label: 'Вверх',
                            val: 'simple_top'
                        },
                        {
                            label: 'Вниз',
                            val: 'simple_bottom'
                        },
                        {
                            label: 'Aventos HL',
                            val: 'front_top'
                        },
                        {
                            label: 'Aventos HF',
                            val: 'double_top'
                        },
                        {
                            label: 'Купе направо',
                            val: 'coupe_r'
                        },
                        {
                            label: 'Купе налево',
                            val: 'coupe_l'
                        },
                        {
                            label: 'Фальшфасад (без открывания)',
                            val: 'falsefacade'
                        }
                    ]
                },
                {
                    key: 'handle_position',
                    name: 'Положение ручки',
                    type: 'select',
                    values: [
                        {
                            label: 'Сверху',
                            val: 'top'
                        },
                        {
                            label: 'По центру',
                            val: 'center'
                        },
                        {
                            label: 'Снизу',
                            val: 'bottom'
                        }
                    ]
                },
                {
                    key: 'handle_orientation',
                    name: 'Ориентация ручки',
                    type: 'select',
                    values: [
                        {
                            label: 'Авто',
                            val: undefined
                        },
                        {
                            label: 'Вертикальная',
                            val: 'vertical'
                        },
                        {
                            label: 'Горизонтальная',
                            val: 'horizontal'
                        }
                    ]
                },
                {
                    key: 'style',
                    name: 'Тип',
                    type: 'select',
                    values: [
                        {
                            label: 'Авто',
                            val: undefined
                        },
                        {
                            label: 'Глухой',
                            val: 'full'
                        },
                        {
                            label: 'Витрина',
                            val: 'window'
                        },
                        {
                            label: 'Решетка',
                            val: 'frame'
                        }
                    ]
                },
                {
                    key: 'fixed_facade_model',
                    name: 'Фиксированная модель фасада',
                    type: 'filemanager'
                }
            ]
        },
        {
            key: 'size',
            name: 'Размеры',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pos',
            name: 'Позиция',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pivot',
            name: 'Точка отсчета',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'select',
                    values: [
                        {
                            label: 'Слева',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Справа',
                            val: 1
                        }
                    ],
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'select',
                    values: [
                        {
                            label: 'Снизу',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сверху',
                            val: 1
                        },
                    ],
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'select',
                    values: [
                        {
                            label: 'Спереди',
                            val: 1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сзади',
                            val: -1
                        },
                    ],
                }
            ]
        },
        {
            key: 'rot',
            name: 'Поворот',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'condition',
            name: 'Условие',
            type: 'input_cond'
        },
    ],
    locker: [
        {
            key: 'spec',
            name: 'Основные параметры',
            children: [
                {
                    key: 'inner',
                    name: 'Тип',
                    type: 'select',
                    values: [
                        {
                            label: 'Обычный',
                            val: false
                        },
                        {
                            label: 'Внутренний',
                            val: true
                        }
                    ]
                },
                {
                    key: 'handle_position',
                    name: 'Положение ручки',
                    type: 'select',
                    values: [
                        {
                            label: 'Авто',
                            val: undefined
                        },
                        {
                            label: 'Сверху',
                            val: 'top'
                        },
                        {
                            label: 'По центру',
                            val: 'middle'
                        }
                    ]
                },
                {
                    key: 'handles_double',
                    name: 'Две ручки',
                    type: 'select',
                    values: [
                        {
                            label: 'Нет',
                            val: undefined
                        },
                        {
                            label: 'Да',
                            val: 1
                        }
                    ]
                },
                {
                    key: 'style',
                    name: 'Фасад',
                    type: 'select',
                    values: [
                        {
                            label: 'Авто',
                            val: undefined
                        },
                        {
                            label: 'Глухой',
                            val: 'full'
                        },
                        {
                            label: 'Витрина',
                            val: 'window'
                        },
                        {
                            label: 'Решетка',
                            val: 'frame'
                        }
                    ]
                },
                {
                    key: 'fixed_facade_model',
                    name: 'Фиксированная модель фасада',
                    type: 'filemanager'
                }
            ]
        },
        {
            key: 'size',
            name: 'Размеры',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pos',
            name: 'Позиция',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pivot',
            name: 'Точка отсчета',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'select',
                    values: [
                        {
                            label: 'Слева',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Справа',
                            val: 1
                        }
                    ],
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'select',
                    values: [
                        {
                            label: 'Снизу',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сверху',
                            val: 1
                        },
                    ],
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'select',
                    values: [
                        {
                            label: 'Спереди',
                            val: 1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сзади',
                            val: -1
                        },
                    ],
                }
            ]
        },
        {
            key: 'rot',
            name: 'Поворот',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'condition',
            name: 'Условие',
            type: 'input_cond'
        },
    ],
    locker2: [
        {
            key: 'spec',
            name: 'Основные параметры',
            children: [
                {
                    key: 'id',
                    name: 'Выбранная модель',
                    type: 'item_picker',
                    catalog: 'lockers'
                },
                {
                    key: 'interactive',
                    name: 'Интерактивность',
                    type: 'select',
                    values: [
                        {
                            label: 'Нет',
                            val: 0
                        },
                        {
                            label: 'Да',
                            val: 1
                        }
                    ],
                },
            ]
        },
        {
            key: 'size',
            name: 'Размеры',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pos',
            name: 'Позиция',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pivot',
            name: 'Точка отсчета',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'select',
                    values: [
                        {
                            label: 'Слева',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Справа',
                            val: 1
                        }
                    ],
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'select',
                    values: [
                        {
                            label: 'Снизу',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сверху',
                            val: 1
                        },
                    ],
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'select',
                    values: [
                        {
                            label: 'Спереди',
                            val: 1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сзади',
                            val: -1
                        },
                    ],
                }
            ]
        },
        {
            key: 'rot',
            name: 'Поворот',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'condition',
            name: 'Условие',
            type: 'input_cond'
        },
    ],
    model: [
        {
            key: 'spec',
            name: 'Основные параметры',
            children: [
                {
                    key: 'id',
                    name: 'Выбранная модель',
                    type: 'item_picker',
                    catalog: 'model3d'
                }
            ]
        },
        {
            key: 'size',
            name: 'Размеры',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pos',
            name: 'Позиция',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pivot',
            name: 'Точка отсчета',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'select',
                    values: [
                        {
                            label: 'Слева',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Справа',
                            val: 1
                        }
                    ],
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'select',
                    values: [
                        {
                            label: 'Снизу',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сверху',
                            val: 1
                        },
                    ],
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'select',
                    values: [
                        {
                            label: 'Спереди',
                            val: 1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сзади',
                            val: -1
                        },
                    ],
                }
            ]
        },
        {
            key: 'rot',
            name: 'Поворот',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'condition',
            name: 'Условие',
            type: 'input_cond'
        },
    ],
    link: [
        {
            key: 'spec',
            name: 'Основные параметры',
            children: [
                {
                    key: 'id',
                    name: 'Выбранная модель',
                    type: 'item_picker',
                    catalog: 'catalogue'
                }
            ]
        },
        {
            key: 'size',
            name: 'Размеры',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pos',
            name: 'Позиция',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pivot',
            name: 'Точка отсчета',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'select',
                    values: [
                        {
                            label: 'Слева',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Справа',
                            val: 1
                        }
                    ],
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'select',
                    values: [
                        {
                            label: 'Снизу',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сверху',
                            val: 1
                        },
                    ],
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'select',
                    values: [
                        {
                            label: 'Спереди',
                            val: 1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сзади',
                            val: -1
                        },
                    ],
                }
            ]
        },
        {
            key: 'rot',
            name: 'Поворот',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'condition',
            name: 'Условие',
            type: 'input_cond'
        },
    ],
    point: [
        {
            key: 'spec',
            name: 'Основные параметры',
            children: [
                {
                    key: 'group',
                    name: 'Группа',
                    type: 'number',
                }
            ]
        },
        {
            key: 'pos',
            name: 'Позиция',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'rot',
            name: 'Поворот',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'condition',
            name: 'Условие',
            type: 'input_cond'
        },
    ],
    facade: [
        {
            key: 'spec',
            name: 'Основные параметры',
            children: [
                {
                    key: 'id',
                    name: 'Выбранная модель',
                    type: 'item_picker',
                    catalog: 'facades'
                },
                {
                    key: 'role',
                    name: 'Роль фасада',
                    type: 'select',
                    values: [
                        {
                            label: 'Корпусная мебель',
                            val: 'cab'
                        },
                        {
                            label: 'Кухни',
                            val: 'kit'
                        }
                    ],
                }
            ]
        },
        {
            key: 'spec.handle',
            name: 'Параметры ручек',
            children: [
                {
                    key: 'active',
                    name: 'Устанавливать ручку',
                    type: 'bool',
                },
                {
                    key: 'h_group',
                    name: 'Группа ручек',
                    type: 'select',
                    values: [
                        {
                            label: 'Дверь',
                            val: 0
                        },
                        {
                            label: 'Ящик',
                            val: 1
                        }
                    ],
                    conditions: [
                        {
                            key: 'spec.handle.active',
                            cond: '=',
                            val: 1
                        }
                    ]
                },
                {
                    key: 'fixed',
                    name: 'Фиксированная модель ручки',
                    type: 'bool',
                    conditions: [
                        {
                            key: 'spec.handle.active',
                            cond: '=',
                            val: 1
                        }
                    ]
                },
                {
                    key: 'id',
                    name: 'Модель ручки',
                    type: 'item_picker',
                    catalog: 'handles',
                    unselect: true,
                    conditions: [
                        {
                            key: 'spec.handle.fixed',
                            cond: '=',
                            val: 1
                        }
                    ]
                },
                {
                    key: 'p_size',
                    name: 'Размер ручки',
                    type: 'select_dynamic',
                    values: 'sizes',
                    conditions: [
                        {
                            key: 'spec.handle.fixed',
                            cond: '=',
                            val: 1
                        },
                        {
                            key: 'spec.handle.id',
                            cond: '!=',
                            val: 0
                        }
                    ]
                },
                {
                    key: 'orient',
                    name: 'Ориентация',
                    type: 'select',
                    values: [
                        {
                            label: 'От параметров модуля',
                            val: 'a'
                        },
                        {
                            label: 'Вертикальная',
                            val: 'v'
                        },
                        {
                            label: 'Горизонтальная',
                            val: 'h'
                        }
                    ],
                    conditions: [
                        {
                            key: 'spec.handle.active',
                            cond: '=',
                            val: 1
                        }
                    ]
                },
                {
                    key: 'pos.x',
                    name: 'Позиция по горизонтали',
                    type: 'select',
                    values: [
                        {
                            label: 'От параметров модуля',
                            val: 'a'
                        },
                        {
                            label: 'Слева',
                            val: 'l'
                        },
                        {
                            label: 'По центру',
                            val: 'c'
                        },
                        {
                            label: 'Справа',
                            val: 'r'
                        },

                    ],
                    conditions: [
                        {
                            key: 'spec.handle.active',
                            cond: '=',
                            val: 1
                        }
                    ]
                },
                {
                    key: 'pos.y',
                    name: 'Позиция по вертикали',
                    type: 'select',
                    values: [
                        {
                            label: 'От параметров модуля',
                            val: 'a'
                        },
                        {
                            label: 'Сверху',
                            val: 't'
                        },
                        {
                            label: 'По центру',
                            val: 'c'
                        },
                        {
                            label: 'Снизу',
                            val: 'b'
                        }
                    ],
                    conditions: [
                        {
                            key: 'spec.handle.active',
                            cond: '=',
                            val: 1
                        }
                    ]
                },
                {
                    key: 'double',
                    name: 'Две ручки',
                    type: 'bool',
                    conditions: [
                        {
                            key: 'spec.handle.active',
                            cond: '=',
                            val: 1
                        }
                    ]
                },
            ]
        },
        {
            key: 'size',
            name: 'Размеры',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pos',
            name: 'Позиция',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pivot',
            name: 'Точка отсчета',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'select',
                    values: [
                        {
                            label: 'Слева',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Справа',
                            val: 1
                        }
                    ],
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'select',
                    values: [
                        {
                            label: 'Снизу',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сверху',
                            val: 1
                        },
                    ],
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'select',
                    values: [
                        {
                            label: 'Спереди',
                            val: 1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сзади',
                            val: -1
                        },
                    ],
                }
            ]
        },
        {
            key: 'rot',
            name: 'Поворот',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'condition',
            name: 'Условие',
            type: 'input_cond'
        },
    ],
    slider: [
        {
            key: 'spec',
            name: 'Основные параметры',
            children: [
                {
                    key: 'ext_percent',
                    name: '% выдвижения',
                    type: 'number'
                },
                {
                    key: 'direction',
                    name: 'Направление движения',
                    type: 'select',
                    values: [
                        {
                            label: 'Вперед',
                            val: 'f'
                        },
                        {
                            label: 'Налево',
                            val: 'l'
                        },
                        {
                            label: 'Направо',
                            val: 'r'
                        },
                        {
                            label: 'Вверх',
                            val: 't'
                        },
                        {
                            label: 'Вниз',
                            val: 'b'
                        },

                    ],
                }
            ]
        },
        {
            key: 'size',
            name: 'Размеры',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pos',
            name: 'Позиция',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pivot',
            name: 'Точка отсчета',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'select',
                    values: [
                        {
                            label: 'Слева',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Справа',
                            val: 1
                        }
                    ],
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'select',
                    values: [
                        {
                            label: 'Снизу',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сверху',
                            val: 1
                        },
                    ],
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'select',
                    values: [
                        {
                            label: 'Спереди',
                            val: 1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сзади',
                            val: -1
                        },
                    ],
                }
            ]
        },
        {
            key: 'rot',
            name: 'Поворот',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'condition',
            name: 'Условие',
            type: 'input_cond'
        },
    ],
    rotator: [
        {
            key: 'spec',
            name: 'Основные параметры',
            children: [
                {
                    key: 'deg',
                    name: 'Угол поворота',
                    type: 'number'
                },
                {
                    key: 'direction',
                    name: 'Направление',
                    type: 'select',
                    values: [
                        {
                            label: 'Ось X (По часовой)',
                            val: 'rx'
                        },
                        {
                            label: 'Ось X (Против часовой)',
                            val: 'lx'
                        },
                        {
                            label: 'Ось Y (По часовой)',
                            val: 'ly'
                        },
                        {
                            label: 'Ось Y (Против часовой)',
                            val: 'ry'
                        },
                        {
                            label: 'Ось Z (По часовой)',
                            val: 'lz'
                        },
                        {
                            label: 'Ось Z (Против часовой)',
                            val: 'rz'
                        },

                    ],
                }
            ]
        },
        {
            key: 'size',
            name: 'Размеры',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pos',
            name: 'Позиция',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pivot',
            name: 'Точка отсчета',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'select',
                    values: [
                        {
                            label: 'Слева',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Справа',
                            val: 1
                        }
                    ],
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'select',
                    values: [
                        {
                            label: 'Снизу',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сверху',
                            val: 1
                        },
                    ],
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'select',
                    values: [
                        {
                            label: 'Спереди',
                            val: 1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сзади',
                            val: -1
                        },
                    ],
                }
            ]
        },
        {
            key: 'rot',
            name: 'Поворот',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'condition',
            name: 'Условие',
            type: 'input_cond'
        },
    ],
    handle: [
        {
            key: 'spec',
            name: 'Основные параметры',
            children: [
                {
                    key: 'id',
                    name: 'Выбранная модель',
                    type: 'item_picker',
                    catalog: 'handles'
                },
                {
                    key: 'size_index',
                    name: 'Размер',
                    type: 'select_dynamic',
                    values: 'sizes'
                }
            ]
        },
        {
            key: 'pos',
            name: 'Позиция',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pivot',
            name: 'Точка отсчета',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'select',
                    values: [
                        {
                            label: 'Слева',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Справа',
                            val: 1
                        }
                    ],
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'select',
                    values: [
                        {
                            label: 'Снизу',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сверху',
                            val: 1
                        },
                    ],
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'select',
                    values: [
                        {
                            label: 'Спереди',
                            val: 1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сзади',
                            val: -1
                        },
                    ],
                }
            ]
        },
        {
            key: 'rot',
            name: 'Поворот',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'condition',
            name: 'Условие',
            type: 'input_cond'
        },
    ],
    blocker: [
        {
            key: 'spec',
            name: 'Основные параметры',
            children: [

            ]
        },
        {
            key: 'size',
            name: 'Размеры',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pos',
            name: 'Позиция',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'pivot',
            name: 'Точка отсчета',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'select',
                    values: [
                        {
                            label: 'Слева',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Справа',
                            val: 1
                        }
                    ],
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'select',
                    values: [
                        {
                            label: 'Снизу',
                            val: -1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сверху',
                            val: 1
                        },
                    ],
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'select',
                    values: [
                        {
                            label: 'Спереди',
                            val: 1
                        },
                        {
                            label: 'По центру',
                            val: 0
                        },
                        {
                            label: 'Сзади',
                            val: -1
                        },
                    ],
                }
            ]
        },
        {
            key: 'rot',
            name: 'Поворот',
            children: [
                {
                    key: 'x',
                    name: 'По оси X',
                    type: 'input_ext'
                },
                {
                    key: 'y',
                    name: 'По оси Y',
                    type: 'input_ext'
                },
                {
                    key: 'z',
                    name: 'По оси Z',
                    type: 'input_ext'
                }
            ]
        },
        {
            key: 'condition',
            name: 'Условие',
            type: 'input_cond'
        },
    ],
}
let it_types = [
    {
        name: 'Секция',
        key: 'section'
    },
    {
        name: 'Деталь',
        key: 'wall'
    },
    {
        name: 'Дверь',
        key: 'door'
    },
    {
        name: 'Ящик (устаревший)',
        key: 'locker'
    },
    {
        name: 'Параметрический элемент',
        key: 'locker2'
    },
    {
        name: 'Слайдер',
        key: 'slider'
    },
    {
        name: 'Элемент вращения',
        key: 'rotator'
    },
    {
        name: 'Модель',
        key: 'model'
    },
    {
        name: 'Элемент каталога',
        key: 'link'
    },
    {
        name: 'Фасад',
        key: 'facade'
    },
    {
        name: 'Ручка',
        key: 'handle'
    },
    {
        name: 'Столешница',
        key: 'tabletop'
    },
    {
        name: 'Точка привязки',
        key: 'point'
    },
    {
        name: 'Блокирующий элемент',
        key: 'blocker'
    }
];
let it_types_names = {
    section: 'Секция',
    wall: 'Деталь',
    door: 'Дверь',
    locker: 'Ящик',
    rotator: 'Элемент вращения',
    model: 'Модель',
    link: 'Элемент каталога',
    facade: 'Фасад',
    handle: 'Ручка',
    tabletop: 'Столешница',
    point: 'Точка привязки',
    blocker: 'Блокирующий элемент'
}
let wall_types = {
    common: {
        name: 'Обычная',
        params: {}
    },
    model: {
        name: '3D модель',
        params: {
            model: {
                key: 'model',
                name: 'Модель',
                type: 'file',
                default: '',
            }
        }
    },
    cut_corners: {
        name: 'Обрезанные углы',
        params: {
            cbl: {
                key: 'cbl',
                name: 'Нижний левый',
                type: 'input_ext',
                default: 0
            },
            ctl: {
                key: 'ctl',
                name: 'Верхний левый',
                type: 'input_ext',
                default: 0
            },
            ctr: {
                key: 'ctr',
                name: 'Верхний правый',
                type: 'input_ext',
                default: 0
            },
            cbr: {
                key: 'cbr',
                name: 'Нижний правый',
                type: 'input_ext',
                default: 0
            },
        }
    },

    triangle: {
        name: 'Треугольник',
        params: {
            corner: {
                key: 'corner',
                name: 'Угол',
                type: 'select',
                default: 'tr',
                values: [
                    {
                        label: 'Верхний правый',
                        val: 'tr'
                    },
                    {
                        label: 'Нижний правый',
                        val: 'br'
                    },
                    {
                        label: 'Верхний левый',
                        val: 'tl'
                    },
                    {
                        label: 'Нижний левый',
                        val: 'bl'
                    }
                ]
            }
        }
    },

    rounded_corners: {
        name: 'Радиусные углы',
        params: {
            cbl: {
                key: 'cbl',
                name: 'Нижний левый',
                type: 'input_ext',
                default: 0
            },
            ctl: {
                key: 'ctl',
                name: 'Верхний левый',
                type: 'input_ext',
                default: 0
            },
            ctr: {
                key: 'ctr',
                name: 'Верхний правый',
                type: 'input_ext',
                default: 0
            },
            cbr: {
                key: 'cbr',
                name: 'Нижний правый',
                type: 'input_ext',
                default: 0
            },
        }
    },
    skewed_by_angle: {
        name: 'Скошенная по торцам',
        params: {
            a1: {
                key: 'a1',
                name: 'Угол слева',
                type: 'input_ext',
                default: 45
            },
            a2: {
                key: 'a2',
                name: 'Угол справа',
                type: 'input_ext',
                default: 45
            },
            type: {
                key: 'type',
                name: 'Торцы',
                type: 'select',
                default: 'x',
                values: [
                    {
                        label: 'Слева/Справа',
                        val: 'x'
                    },
                    {
                        label: 'Сверху/Снизу',
                        val: 'y'
                    }
                ]
            }
        }
    },
    trapeze_cut_by_offset: {
        name: 'Трапеция',
        params: {
            w1: {
                key: 'w1',
                name: 'Ширина спереди',
                type: 'input_ext',
                default: 150
            },
            h1: {
                key: 'h1',
                name: 'Высота спереди',
                type: 'input_ext',
                default: 150
            },
            w2: {
                key: 'w2',
                name: 'Ширина сзади',
                type: 'input_ext',
                default: 75
            },
            h2: {
                key: 'h2',
                name: 'Высота сзади',
                type: 'input_ext',
                default: 75
            },
            o: {
                key: 'o',
                name: 'Ориентация',
                type: 'select',
                default: 'left',
                values: [
                    {
                        label: 'Левая',
                        val: 'left'
                    },
                    {
                        label: 'Правая',
                        val: 'right'
                    }
                ]
            }
        }
    },
    half_rounded: {
        name: 'Радиус',
        params: {
            orientation: {
                key: 'orientation',
                name: 'Ориентация',
                type: 'select',
                default: 'left',
                values: [
                    {
                        label: 'Левая',
                        val: 'left'
                    },
                    {
                        label: 'Правая',
                        val: 'right'
                    }
                ]
            },
            radius: {
                key: 'radius',
                name: 'Радиус',
                type: 'input_ext',
                default: 150
            },
        }
    },
    free: {
        name: 'Свободная',
        params: {
            points: {
                key: 'points',
                name: 'Точки',
                type: 'array',
                value: {
                    x: 0,
                    y: 0
                },
                value_name: 'Координаты',
                default: [
                    {
                        x: 0,
                        y: 0
                    }
                ],
                values: [

                ]
            }
        }
    }
}

let can_add = {
    'section': 1,
    'divider': 1,
    'slider': 1,
    'rotator': 1,
    'facade': 1
}






let editor;

Vue.component('material_input', {
    template: '#material_input_template',
    props: {
        params: {
            type: Object,
            default: function () {
                return {};
            }
        },

        materials: {
            type: Object,
            default: function () {
                return {};
            }
        },
        group: {
            type: Number,
            default: 0
        },
        label: String,
        value: [Number, String],
        delay: {
            type: Number,
            default: typeof input_delay === 'undefined' ? 500 : input_delay
        }
    },
    data: function () {
        return {
            l_params: {},
        };
    },
    computed: {},
    watch: {
        params: function () {
            this.cr();
        }
    },
    created: function () {
        this.cr();
    },
    mounted: function () {

    },
    methods: {
        cr: function () {
            console.log(this.params)
            if (!this.params.group) this.params.group = 0
            Vue.set(this, 'l_params', this.params)
            this.$options.materials = set_obj.materials;
        },
        change_material: function () {
            this.l_params.variant = 'current';
            this.l_params.id = 0;
            this.$emit('change_material', copy_object(this.l_params))
        },
        change_group: function () {
            this.$emit('change_group', copy_object(this.l_params))
        },
        change_drag_mode: function () {
            this.$emit('change_drag_mode', copy_object(this.l_params))
        },
        change_variant: function () {
            this.$emit('change_variant', copy_object(this.l_params))
        },
        change_rotated: function () {
            this.$emit('change_rotated', copy_object(this.l_params.rotated))
        },
        change_fixed: function () {
            this.$emit('change_fixed', copy_object(this.l_params.fixed))
        },
        lang: function (key) {
            return lang[key]
        },
    }
})

Vue.component('value_input', {
    template: '#value_input_template',
    props: {
        var_list: {
            type: Object,
            default: function () {
                return {};
            }
        },
        label: String,
        axis: {
            type: String,
            default: ''
        },
        value: [Number, String],
        delay: {
            type: Number,
            default: typeof input_delay === 'undefined' ? 500 : input_delay
        }
    },
    data: function () {
        return {
            show_variables: 0,
            errors: 0,
            pretty_val: '',
            real_val: '',
            mode: 'pretty',
            timeout: null
        };
    },
    computed: {
        mode_button_content: function () {
            return this.mode == 'pretty' ? '{aa}' : 'a:a'
        }
    },
    watch: {
        value: function () {
            this.real_val = this.value;
            this.pretty_val = this.value;
            this.from_real();
        }
    },
    created: function () {

        this.real_val = this.value;
        this.pretty_val = this.value;
        this.from_real();

        let scope = this;

        document.addEventListener('mousedown', function (e) {
            var element = scope.$refs.var_list;

            if (element) {
                if (e.target !== element && !element.contains(e.target)) {
                    scope.show_variables = false;
                }
            }


        })

    },
    mounted: function () {

    },
    methods: {
        get_style: function () {
            if (this.axis == 'x') return {
                color: '#e10000'
            }
            if (this.axis == 'y') return {
                color: '#67952c'
            }
            if (this.axis == 'z') return {
                color: '#4c95f7'
            }
            return {};
        },
        change_mode: function () {
            if (this.mode == 'pretty') {
                this.mode = 'real'
            } else {
                this.mode = 'pretty'
            }
        },
        add_val: function (item, modifier = '') {
            if (modifier == '') {
                this.pretty_val = '{' + item.name + '}';
            } else {
                this.pretty_val += modifier + '{' + item.name + '}'
            }
            this.show_variables = 0;
            this.from_pretty()
            this.on_change();
        },

        add_val_mat: function (item, variant = null, modifier = '') {

            let name;

            if (variant) {
                name = '{' + item.name + ' ' + variant.name + '::' + item.id + '}';
            } else {
                name = '{' + item.name + ' текущий' + '::' + item.id + '}';
            }


            if (modifier == '') {
                this.pretty_val = name;
            } else {
                this.pretty_val += modifier + name
            }

            this.show_variables = 0;
            this.from_pretty()
            this.on_change();
        },


        change_pretty: function () {
            this.from_pretty();
            this.on_change();
        },
        change_real: function () {
            this.from_real();
            this.on_change();
        },

        from_pretty: function () {
            let ch = treg(this.pretty_val);
            let str = copy_object(this.pretty_val);


            for (let i = 0; i < ch.length; i++) {
                let v = '{' + ch[i] + '}';
                console.log(v)
                if (!this.var_list.hash[ch[i]]) {
                    this.errors = 1;
                    str = str.replaceAll(v, '')
                } else {
                    str = str.replaceAll(v, this.var_list.hash[ch[i]])
                }
            }


            str = str.replace(/ +/g, '');
            str = str.replace(/(\*|\(|\)|\+|-|\/)/g, ' $1 ')
            str = str.replace(/\s\s+/g, ' ');
            this.real_val = str;


            if (this.pretty_val == '') this.pretty_val = 0;
            if (this.real_val == '') this.real_val = 0;

        },
        from_real: function () {

            if (typeof this.real_val == 'string') {
                let str = this.real_val;

                let arr = str.split(' ')

                let res

                for (let i = 0; i < arr.length; i++) {
                    if (isNaN(parseInt(arr[i]))) {

                        switch (arr[i][0]) {
                            case 'v':
                                arr[i] = '{' + this.var_list.variables[arr[i].substring(2)].name + '}'
                                break;
                            case 'c':
                                arr[i] = '{' + this.var_list.computed[arr[i].substring(2)].name + '}'
                                break;
                            case 's':
                                if (arr[i].indexOf('x') > -1) {
                                    arr[i] = '{Ширина}'
                                }
                                if (arr[i].indexOf('y') > -1) {
                                    arr[i] = '{Высота}'
                                }
                                if (arr[i].indexOf('z') > -1) {
                                    arr[i] = '{Глубина}'
                                }
                                break;
                            case 'm':
                                if (arr[i].indexOf('current') > -1) {
                                    let spl = arr[i].split('.current.');
                                    let key = spl[0].substring(2);
                                    let material = this.var_list.materials[key];
                                    let name = material.name;

                                    arr[i] = '{' + name + ' текущий' + '::' + material.id + '}'

                                } else if (arr[i].indexOf('v_') > -1) {

                                    let spl = arr[i].split('.');
                                    let key = spl[0].substring(2);
                                    let var_key = spl[1];
                                    let material = this.var_list.materials[key];
                                    let name = material.name;
                                    let variant = material.variants[var_key]

                                    arr[i] = '{' + name + ' ' + variant.name + '::' + material.id + '}'

                                } else {

                                }
                                break;
                            default:
                                arr[i] = arr[i]
                        }

                    } else {
                        arr[i] = parseInt(arr[i])
                    }

                }


                this.pretty_val = arr.join(' ')
                if (this.pretty_val == '') this.pretty_val = 0;
                if (this.real_val == '') this.real_val = 0;
            } else {
                if (this.real_val == '') this.real_val = 0;
                this.pretty_val = this.real_val;
            }


        },

        show_var_list: function (e) {

            let rect = e.target.getBoundingClientRect();

            this.$refs.var_list.style.top = 'auto';
            this.$refs.var_list.style.bottom = 'auto';
            this.$refs.var_list.style.left = 'auto';
            this.$refs.var_list.style.right = 'auto';
            this.$refs.var_list.style.left = rect.left + 'px'
            this.$refs.var_list.style.top = rect.top + 'px'

            let vc = is_out_of_viewport(this.$refs.var_list);

            if (vc.bottom) {
                this.$refs.body.style.top = 'auto';
                this.$refs.body.style.bottom = 20 + 'px'
            }

            if (vc.right) {
                this.$refs.body.style.left = 'auto';
                this.$refs.body.style.right = 20 + 'px'
            }

            this.show_variables = !this.show_variables;
        },

        on_change: function (e) {
            clearTimeout(this.debounce)
            let scope = this;
            scope.$emit('change_inp', scope.real_val)

            this.debounce = setTimeout(function () {

            }, this.delay)
        },

        apply_val: function () {
            this.on_change();
        },

        // on_change: function (e) {
        //     let scope = this;
        //     clearTimeout(this.$options.timeout)
        //     this.$options.timeout = setTimeout(function () {
        //         console.log(scope.real_val)
        //         scope.$emit('change', scope.real_val)
        //     }, this.delay)
        // },

        lang: function (key) {
            return lang[key]
        },
    }
})

Vue.component('condition_input', {
    template: '#condition_input_template',
    props: {
        var_list: {
            type: Object,
            default: function () {
                return {};
            }
        },
        label: String,
        value: [Number, String],
        delay: {
            type: Number,
            default: typeof input_delay === 'undefined' ? 500 : input_delay
        }
    },
    data: function () {
        return {
            show_variables: 0,
            errors: 0,
            variable: null,
            val: 0,
            timeout: null
        };
    },
    computed: {},
    watch: {
        value: function () {
            this.cr();
        }
    },
    created: function () {
        this.cr();
    },
    mounted: function () {

    },
    methods: {
        cr: function () {
            if (this.value) {
                let arr = this.value.split('=')
                this.variable = arr[0].substring(2);
                this.val = arr[1];
            }
        },
        on_change: function () {
            if (this.variable == null) {
                this.$emit('change_inp', null)
            }
        },
        apply_val: function () {
            if (!this.val) this.val = 0;
            let res = 'v:' + this.variable + '=' + this.val
            this.$emit('change_inp', res)
        },

        lang: function (key) {
            return lang[key]
        },
    }
})

Vue.component('filemanager_input', {
    template: '#filemanager_input_template',
    props: {
        value: String
    },
    data: function () {
        return {};
    },
    computed: {},
    watch: {},
    created: function () {

    },
    mounted: function () {

    },
    methods: {
        sel_file2(file) {
            this.value = file.true_path;
            this.on_change();
        },
        on_change() {
            this.$emit('change_inp', this.value)
        }
    }
})

Vue.component('configurator', {
    template: '#configurator_template',
    components: {SlVueTree},
    props: {
        root_name: {
            type: String,
            default: 'Модуль'
        },
        tab_variables: {
            type: Number,
            default: 1
        },
        tab_computed: {
            type: Number,
            default: 0
        },
        tab_tree: {
            type: Number,
            default: 1
        },
        tab_variants: {
            type: Number,
            default: 1
        },
        tab_common: {
            type: Number,
            default: 1
        },
        tab_materials: {
            type: Number,
            default: 1
        },
        tab_events: {
            type: Number,
            default: 1
        },
        tab_json: {
            type: Number,
            default: 1
        },
        excluded_items: {
            type: Array,
            default: function () {
                return []
            }
        },
    },
    data: function () {
        return {
            show: 1,

            mode: 'back',

            current_node: null,

            modal_add: {
                show: 0,
                node: null,
                type: 'section',
                name: 'Секция',
                model_id: 0,
                params: {}
            },

            common_params: {},

            var_add_vis: 0,
            variable: {
                name: '',
                key: '',
                type: 'number',
                value: 0,
                local: 0,
                editable: 0,
                variants: 0,
                params: {
                    min: 1,
                    max: '',
                    step: 1,
                }
            },

            variant: 0,
            variants: [],

            events: {},

            tab: 'tree',
            nodes: [],
            node_context_visible: false,
            node_can_add: true,
            node_is_root: false,

            lastEvent: 'No last event',
            selectedNodesTitle: '',
            current_params: '',
            variables: {},
            computed: {},
            cab_materials: [],
            delay: typeof input_delay === 'undefined' ? 500 : input_delay,
            debounce: null,
        };
    },
    computed: {
        var_list: function () {
            let scope = this;
            let res = copy_object({
                sizes: {
                    x: {
                        key: 'x',
                        name: 'Ширина'
                    },
                    y: {
                        key: 'x',
                        name: 'Высота'
                    },
                    z: {
                        key: 'x',
                        name: 'Глубина'
                    }
                },
                variables: this.variables,
                computed: this.computed,
                materials: copy_object(materials)
            })

            let hash = {};

            Object.keys(this.variables).forEach(function (k) {
                hash[scope.variables[k].name] = 'v:' + k;
            })

            Object.keys(this.computed).forEach(function (k) {
                hash[scope.computed[k].name] = 'c:' + k;
            })

            Object.keys(res.materials).forEach(function (k) {

                let mat = res.materials[k];
                hash[mat.name + ' текущий' + '::' + mat.id] = 'm:' + k + '.current.size.z'
                Object.keys(mat.variants).forEach(function (mk) {
                    hash[mat.name + ' ' + mat.variants[mk].name + '::' + mat.id] = 'm:' + k + '.' + mk + '.size.z'
                })

            })

            res.hash = hash;
            res.hash['Высота'] = 's:y';
            res.hash['Ширина'] = 's:x';
            res.hash['Глубина'] = 's:z';


            return copy_object(res)
        },
    },

    created: function () {

        if(typeof configurator_init_callback == 'function') configurator_init_callback();

        options.wall[0].children[1].values = [];
        Object.keys(wall_types).forEach(function (k) {
            options.wall[0].children[1].values.push({
                val: k,
                label: wall_types[k].name
            })
            options.tabletop[0].children[1].values.push({
                val: k,
                label: wall_types[k].name
            })
        })




        let scope = this;
        this.$options.opts = options
        this.$options.wall_types = wall_types;
        this.$options.models = model_data.items;
        this.$options.clipboard = null;
        this.$options.clipboardApi = false;

        window.navigator.permissions.query({name: 'clipboard-read'})
            .then((result) => {
                console.log(123123)
                console.log(result)
                if (result.state == 'granted' || result.state == 'prompt') {
                    this.$options.clipboardApi = true;
                }
            });

        window.addEventListener("scroll", (event) => {
            this.node_context_visible = false;
        });

        document.addEventListener('mousedown', function (e) {
            if (scope.node_context_visible) {
                e.preventDefault();
                e.stopPropagation();
                var element = scope.$refs.context_menu;
                if (element) {
                    if (e.target !== element && !element.contains(e.target)) {

                        scope.node_context_visible = false;
                        return false;
                    }
                }
            }
        })


        this.$root.$on('from_json', function (obj) {

        })

        this.$root.$on('add_point_mouse', function (params) {
            scope.add_point_mouse(params)
        })

        this.$root.$on('set_selected', function (obj) {
            if (obj && obj.temp_type && typeof obj.gp === 'function') {
                scope.show = 1;
                scope.reset_tree()
            }
        })

        this.$root.$on('nodeclick', (node, event) => {
            this.node_selected(node, event)
        })


        this.$options.var_params = {
            number: {
                min: 1,
                max: '',
                step: 1,
            },
            select: {
                options: []
            },
            boolean: {}
        }


        if(this.excluded_items.length){
            let exc = {};
            for (let i = this.excluded_items.length; i--;){
                exc[this.excluded_items[i]] = 1;
            }

            for (let i = it_types.length; i--;){
                if (exc[it_types[i].key]) it_types.splice(i, 1)
            }
        }


        this.$options.item_types = it_types;
        this.$options.item_types_names = it_types_names;

    },
    mounted: function () {
        window.configurator = this;
        window.slVueTree = this.$refs.slVueTree;



        const container = document.getElementById("jsoneditor_asfjh")
        const options = {
            search: false,
            mode: 'code',
            modes: ['tree', 'view', 'form', 'code', 'text', 'preview']
        }
        editor = new JSONEditor(container, options)
    },
    methods: {
        get_style: function (axis) {
            if (axis == 'x') return {
                color: '#e10000'
            }
            if (axis == 'y') return {
                color: '#67952c'
            }
            if (axis == 'z') return {
                color: '#4c95f7'
            }
            return {};
        },
        context_menu: async function (node, event) {
            event.preventDefault();
            this.node_context_visible = true;

            if (this.$options.clipboardApi) {
                try {
                    let params = await navigator.clipboard.readText()
                    params = JSON.parse(params)
                    this.$options.clipboard = params;
                } catch (e) {

                }
            } else {

            }


            console.log(node.data.type)

            this.node_can_add = can_add[node.data.type];
            this.node_is_root = node.level == 1;
            this.node_selected(node, event)
            this.$refs.context_menu.style.left = event.clientX + 'px';
            this.$refs.context_menu.style.top = event.clientY - 10 + 'px';
        },

        get_group: function () {
            return this.$root.$selected_object.userData.params.spec.group;
        },
        get_drag_mode: function(){
            return this.$root.$selected_object.userData.params.spec.drag_mode;
        },
        set_group: function (e) {
            this.$root.$selected_object.userData.params.spec.group = e.target.value
            this.$root.$selected_object.cabinet_type = this.$root.$selected_object.userData.params.spec.group

            // this.$root.$selected_object.build()
        },

        set_drag_mode: function (e) {
            this.$root.$selected_object.userData.params.spec.drag_mode = e.target.value

            // this.$root.$selected_object.build()
        },

        set_material_param: function (index, key, val, group) {

            let mat_params = this.$root.$selected_object.userData.params.spec.materials;

            mat_params[index][key] = val;
            let mat_key = mat_params[index].key


            if(mat_params[index].custom != 1){
                this.$root.$selected_object.change_material(mat_key, group, val)
            }





            this.$root.$emit('change_default_material', {
                mat_key: mat_key,
                index: index,
                group: group,
                key: key,
                val: val
            })
        },

        get_mat_cats: function (material) {
            let mat = set_obj.materials[material.key]
            return copy_object(mat.variants[mat.default].categories)
        },

        get_cab_materials: function () {

            this.$root.$selected_object.build()

            let materials = copy_object(this.$root.$selected_object.userData.params.spec.materials);

            console.log(materials)

            for (let i = materials.length; i--;) {
                if (!materials[i].custom && !set_obj.materials[materials[i].key]) materials.splice(i, 1)
            }

            for (let i = 0; i < materials.length; i++) {
                if (!materials[i].label) materials[i].label = set_obj.materials[materials[i].key].name;
                if (!materials[i].fixed) materials[i].fixed = 0;
                if (!materials[i].order) materials[i].order = i;
                if (!materials[i].custom) {
                    if (!materials[i].name) materials[i].name = set_obj.materials[materials[i].key].name;
                }
                if(!materials_lib.items[materials[i].id]) materials[i].id = 0;

            }

            return materials
        },
        get_material_params: function () {
            return copy_object(this.current_params.material)
        },

        choose_param_el: function(e){
            this.modal_add.model_id = e;

            if(this.modal_add.name == ''){
                this.modal_add.name = this.$refs.items_picker.get_item_data().name
            }
        },

        add_variant: function () {
            let scope = this;
            let sel_obj = this.$root.$selected_object
            let obj = {
                name: '',
                code: '',
                price: 0,
                variables: {},
                accessories: []
            }

            if (this.variants.length > 0) {
                let last_variant = this.variants[this.variants.length - 1];
                obj.size = {
                    x: last_variant.size.x,
                    y: last_variant.size.y,
                    z: last_variant.size.z,
                }
                Object.keys(last_variant.variables).forEach(function (k) {
                    obj.variables[k] = {
                        value: last_variant.variables[k].value
                    }
                })
            } else {
                obj.size = {
                    x: 0,
                    y: 0,
                    z: 0
                }
            }

            Object.keys(this.variables).forEach(function (k) {
                if (scope.variables[k].variants == 1) {
                    if (!obj.variables[k]) {
                        obj.variables[k] = {
                            value: scope.variables[k].value
                        }
                    }
                }
            })


            sel_obj.userData.params.spec.variants.push(copy_object(obj))
            this.variants.push(obj)


        },
        change_variant: function (ind) {
            clearTimeout(this.debounce)
            let scope = this;
            this.debounce = setTimeout(function () {
                let sel_obj = scope.$root.$selected_object;
                sel_obj.userData.params.spec.variants[ind] = copy_object(scope.variants[ind]);
                if (ind == scope.variant) {
                    sel_obj.select_variant(scope.variant, true)
                }
            }, this.delay)
        },
        change_variant_accessories: function(ind, e){
            let scope = this;
            let sel_obj = scope.$root.$selected_object;
            scope.variants[ind].accessories = e;
            sel_obj.userData.params.spec.variants[ind].accessories = copy_object(scope.variants[ind].accessories);
        },
        remove_variant: function (ind) {
            this.variants.splice(ind, 1)

            if (!this.variants[this.variant]) {
                this.variant = 0;
                this.$root.$selected_object.userData.params.spec.variant = 0;
            }

            this.$root.$selected_object.userData.params.spec.variants.splice(ind, 1)
        },
        variant_up: function (ind) {
            this.variant = ind - 1;
            this.$root.$selected_object.userData.params.spec.variant = ind + 1
            swap_arr_elements(this.variants, ind, ind - 1);
            swap_arr_elements(this.$root.$selected_object.userData.params.spec.variants, ind, ind - 1);
        },

        variant_down: function (ind) {
            this.variant = ind + 1;
            this.$root.$selected_object.userData.params.spec.variant = ind + 1
            swap_arr_elements(this.variants, ind, ind + 1);
            swap_arr_elements(this.$root.$selected_object.userData.params.spec.variants, ind, ind + 1);
        },

        change_event: function (key) {

            clearTimeout(this.debounce)
            this.debounce = setTimeout(() => {
                let sel_obj = this.$root.$selected_object;
                sel_obj.userData.params.events[key] = this.events[key];
            }, this.delay)


        },

        material_up: function (ind) {
            swap_arr_elements(this.$root.$selected_object.userData.params.spec.materials, ind, ind - 1);
            swap_arr_elements(this.cab_materials, ind, ind - 1);
        },
        material_down: function (ind) {
            swap_arr_elements(this.$root.$selected_object.userData.params.spec.materials, ind, ind + 1);
            swap_arr_elements(this.cab_materials, ind, ind + 1);
        },

        change_def_variant: function () {
            this.$root.$selected_object.userData.params.spec.variant = this.variant
            this.$root.$selected_object.select_variant(this.variant, true)
        },

        show_var_add: function (item) {
            if (item) {
                Vue.set(this, 'variable', copy_object(item))
                this.var_add_vis = 1;
            } else {
                this.var_add_vis = 1;
            }


        },
        hide_var_add: function () {

            this.var_add_vis = 0;

            Vue.set(this, 'variable', {
                name: '',
                key: '',
                type: 'number',
                value: 0,
                editable: 0,
                variants: 0,
                params: {
                    min: 1,
                    max: '',
                    step: 1,
                }
            })
        },

        var_change_name: function () {
            this.variable.key = translit(this.variable.name).toLowerCase().replaceAll(' ', '_');
        },
        var_change_type: function () {
            console.log(this.variable)
            Vue.set(this.variable, 'params', JSON.parse(JSON.stringify(this.$options.var_params[this.variable.type])))

        },
        var_remove_option: function (index) {
            this.variable.params.options.splice(index, 1)
        },
        var_add_option: function () {
            this.variable.params.options.push({
                name: '',
                value: '',
            })
        },

        add_variable: function () {
            ce();
            ce();
            ce();
            console.log(this.variables)
            console.log(this.variants)
            let sel_obj = this.$root.$selected_object;
            let v = copy_object(this.variable)
            sel_obj.userData.params.variables[v.key] = copy_object(v);
            sel_obj.userData.computed.variables[v.key] = copy_object(v);
            // sel_obj.add_variable(copy_object(v), true)
            if (v.variants == 1) {
                for (let i = 0; i < this.variants.length; i++) {
                    if (!this.variants[i].variables[v.key]) {
                        this.variants[i].variables[v.key] = {
                            value: v.value
                        }
                        sel_obj.userData.params.spec.variants[i].variables[v.key] = {
                            value: v.value
                        }
                    }
                }
            } else {
                for (let i = 0; i < this.variants.length; i++) {
                    if (this.variants[i].variables[v.key]) {
                        delete this.variants[i].variables[v.key]
                        delete sel_obj.userData.params.spec.variants[i].variables[v.key]
                    }
                }
            }

            Vue.set(this.variables, v.key, v)

            this.$root.$emit('change_variable', v)

            sel_obj._rebuild_params();
            sel_obj.build();

            this.hide_var_add();
        },
        remove_variable: function (v) {
            let sel_obj = this.$root.$selected_object;
            if (v.variants == 1) {
                for (let i = 0; i < this.variants.length; i++) {
                    delete this.variants[i].variables[v.key]
                    delete sel_obj.userData.params.spec.variants[i].variables[v.key]
                }
            }

            delete sel_obj.userData.params.variables[v.key]
            this.$root.$emit('remove_variable', v.key)
            Vue.delete(this.variables, v.key)



        },

        change_variable: function (key, val, type) {

            if (type == 'number') {
                clearTimeout(this.debounce)
                this.debounce = setTimeout(() => {

                    this.variables[key].value = val
                    let sel_obj = this.$root.$selected_object;
                    sel_obj.change_variable(key, val)
                }, this.delay)
            } else {
                this.variables[key].value = val
                this.$root.$emit('change_variable', this.variables[key])
                let sel_obj = this.$root.$selected_object;
                sel_obj.change_variable(key, val)
            }


        },

        change_add_type: function (e) {
            let fl = this.modal_add.name == '' || this.modal_add.name == this.$options.item_types_names[this.modal_add.type]
            this.modal_add.type = e.target.value
            if (fl == 1) {
                this.modal_add.name = this.$options.item_types_names[this.modal_add.type]
            }
        },

        change_name: function (e) {
            let path = this.$refs.slVueTree.getSelected()[0].path;
            this.$refs.slVueTree.updateNode(path, {title: e.target.value})
            this.current_params.name = e.target.value;
            clearTimeout(this.debounce)
            this.debounce = setTimeout(() => {
                this.$root.$selected_object.change_param(path.join(), 'name', e.target.value)
            }, this.delay)
            this.emit_update();
        },
        change_param_ext: function (k1, k2, val) {
            console.log(k1)
            console.log(k2)
            console.log(val)
            let path = this.$refs.slVueTree.getSelected()[0].path
            this.$root.$selected_object.change_param(path.join(), k1 + '.' + k2, val, true)
            this.current_params[k1][k2] = val;
            this.emit_update();
        },

        change_condition: function (val) {
            let path = this.$refs.slVueTree.getSelected()[0].path
            this.$root.$selected_object.change_param(path.join(), 'condition', val)
            this.current_params.condition = val;
            this.$options.obj.userData.root.build();
            this.emit_update();
        },

        get_dynamic_params: function (values) {
            let res = [];
            switch (this.current_params.type) {
                case 'handle':
                    if(this.current_params.spec.id == 0) return [];

                    let item = handles_lib.items[this.current_params.spec.id];

                    let vals = item[values];
                    for (let i = 0; i < vals.length; i++) {
                        res.push({
                            val: i,
                            label: vals[i].axis_size != '' ? vals[i].axis_size : vals[i].width + 'x' + vals[i].height + 'x' + vals[i].depth
                        })
                    }
                    break;

                default:
                    res = []
            }

            return res;
        },

        check_param_condition: function(param){
            if(!param.conditions) return true;

            let res = true;

            for (let i = 0; i < param.conditions.length; i++){
                let current_param = this.current_params;
                let p_arr = param.conditions[i].key.split('.');
                for (let j = 0; j < p_arr.length; j++){
                    current_param = current_param[p_arr[j]]
                }

                switch (param.conditions[i].cond) {
                    case "=":
                        if(current_param != param.conditions[i].val) res = false;
                        break;
                    case "!=":
                        if(current_param == param.conditions[i].val) res = false;
                        break;
                }

            }

            return res;
        },

        get_current_param: function(key, param_key){
            // console.log(key)
            // console.log(param_key)
            // console.log(this.current_params)
            let arr = key.split('.');
            let start = this.current_params
            for (let i = 0; i < arr.length; i++){
                start = start[arr[i]];
            }
            let arr2 = param_key.split('.')
            if(arr2.length > 1){
                for (let i = 0; i < arr2.length-1; i++){
                    start = start[arr2[i]];
                }
            }
            let val_key = arr2[arr2.length-1]

            return start[val_key]
        },

        change_param: function (k1, k2, e, type) {

            console.log(e)

            let value = '';
            switch (type) {
                case 'checkbox':
                    value = to_int(e.target.checked)
                    break;
                case 'picker':
                    value = e;
                    break;
                case 'ext':
                    value = e;
                    break;
                default:
                    value = e.target.value
            }

            let path = this.$refs.slVueTree.getSelected()[0].path
            // let val = this.current_params[k1][k2];
            let arr = k1.split('.');
            let current_param = this.current_params
            for (let i = 0; i < arr.length; i++){
                current_param = current_param[arr[i]];
            }
            let arr2 = k2.split('.')
            if(arr2.length > 1){
                for (let i = 0; i < arr2.length-1; i++){
                    current_param = current_param[arr2[i]];
                }
            }

            let key = arr2[arr2.length-1]

            console.log(key)

            Vue.set(current_param, key, value)
            let val = value;

            console.log(value)
            console.log(k1 + '.' + k2)


            this.$root.$selected_object.change_param(path.join(), k1 + '.' + k2, val)
            if (this.current_params.type == 'wall' || this.current_params.type == 'tabletop') {

                if (k2 == 'orientation') {
                    switch (val) {
                        case 'f':
                            this.current_params.size.x = 's:x'
                            this.current_params.size.y = 's:y'
                            this.$root.$selected_object.change_param(path.join(), 'size.x', 's:x')
                            this.$root.$selected_object.change_param(path.join(), 'size.y', 's:y')
                            break;
                        case 'v':
                            this.current_params.size.x = 's:z'
                            this.current_params.size.y = 's:y'
                            this.$root.$selected_object.change_param(path.join(), 'size.x', 's:z')
                            this.$root.$selected_object.change_param(path.join(), 'size.y', 's:y')
                            break;
                        case 'h':
                            this.current_params.size.x = 's:x'
                            this.current_params.size.y = 's:z'
                            this.$root.$selected_object.change_param(path.join(), 'size.x', 's:x')
                            this.$root.$selected_object.change_param(path.join(), 'size.y', 's:z')
                            break;
                    }
                }

                if (k2 == 'type') {
                    let type_params = copy_object(this.$options.wall_types[val]).params
                    let obj = {};

                    Object.keys(type_params).forEach(function (k) {
                        console.log(k)
                        console.log(type_params[k].default)
                        obj[k] = type_params[k].default;
                    })


                    Vue.set(this.current_params.spec, 'type_params', copy_object(obj))
                    this.$root.$selected_object.change_param(path.join(), k1 + '.type_params', obj)
                }
            }
            // this.$options.obj.userData.root.build();


            this.$root.$selected_object.build_by_path(path.join())
            this.emit_update();
        },

        change_model: function (k1, k2, e) {
            console.log(k1)
            console.log(k2)
            console.log(e)
        },

        change_facade: function (k1, k2, e) {
            console.log(k1)
            console.log(k2)
            console.log(e)
            let path = this.$refs.slVueTree.getSelected()[0].path
            this.$root.$selected_object.change_param(path.join(), k1 + '.' + k2, e)
            // this.current_params.spec.type_params[key] = e
            this.current_params[k1][k2] = e;
            this.$root.$selected_object.build_by_path(path.join())
            this.emit_update();
        },

        change_handle: async function (k1, k2, e) {
            let path = this.$refs.slVueTree.getSelected()[0].path
            this.$root.$selected_object.change_param(path.join(), k1 + '.' + k2, e)
            this.$root.$selected_object.change_param(path.join(), 'spec.size_index', 0)
            // this.current_params.spec.type_params[key] = e
            this.current_params[k1][k2] = e;
            this.current_params['spec']['size_index'] = 0;
            this.$root.$selected_object.build_by_path(path.join())
            this.emit_update();
        },

        change_default_material: function (material, ind, event) {
            console.log(material)
            console.log(ind)
            console.log(event)
        },


        change_link: function (k1, k2, e) {
            console.log(k1)
            console.log(k2)
            console.log(e)
        },
        change_param_wall_type: function (key, val, is_event = false) {
            if(is_event) val = val.target.value
            let path = this.$refs.slVueTree.getSelected()[0].path
            this.$root.$selected_object.change_param(path.join(), 'spec.type_params.' + key, val, true)
            this.current_params.spec.type_params[key] = val
            this.emit_update();
        },
        change_param_wall_type_array: function (item_key, index, key, e) {
            console.log(item_key + '.' + index + '.' + key)
            let path = this.$refs.slVueTree.getSelected()[0].path
            this.current_params.spec.type_params[item_key][index][key] = e
            let arr_params = copy_object(this.current_params.spec.type_params[item_key])
            this.$root.$selected_object.change_param(path.join(), 'spec.type_params.' + item_key, arr_params, true)
            this.emit_update();

        },
        add_param_wall_type_array: function (key) {
            this.current_params.spec.type_params[key].push(
                copy_object(this.$options.wall_types[this.current_params.spec.type].params[key].value)
            )
        },
        remove_param_wall_type_array: function (key, index) {
            this.current_params.spec.type_params[key].splice(index, 1)
        },

        change_param_material: function (data) {
            let path = this.$refs.slVueTree.getSelected()[0].path

            // if(!this.$options.obj.userData.root.userData.params.materials[data.key + '::' + data.group])
            //     this.$options.obj.userData.root.add_material_key(data.key, data.group)

            this.$root.$selected_object.change_param(path.join(), 'material.key', data.key)
            this.$root.$selected_object.change_param(path.join(), 'material.variant', data.variant)
            this.$root.$selected_object.change_param(path.join(), 'material.id', data.id)
            this.$root.$selected_object.change_param(path.join(), 'material.group', data.group)

            this.$root.$selected_object.build_by_path(path.join())
            this.emit_update();
        },
        change_param_material_variant: function (data) {
            let path = this.$refs.slVueTree.getSelected()[0].path
            // if(!this.$options.obj.userData.root.userData.params.materials[data.key + '::' + data.group])
            //     this.$options.obj.userData.root.add_material_key(data.key, data.group)

            this.$root.$selected_object.change_param(path.join(), 'material.variant', data.variant)
            this.$root.$selected_object.change_param(path.join(), 'material.id', data.id)
            this.$root.$selected_object.build_by_path(path.join())
            this.emit_update();
        },
        change_param_material_group: function (data) {
            let path = this.$refs.slVueTree.getSelected()[0].path
            if (!data.group) data.group = 0;
            // if(!this.$options.obj.userData.root.userData.params.materials[data.key + '::' + data.group])
            //     this.$options.obj.userData.root.add_material_key(data.key, data.group)

            this.$root.$selected_object.change_param(path.join(), 'material.group', data.group)
            this.$root.$selected_object.build_by_path(path.join())
            this.emit_update();
        },


        change_tab: function (key) {
            this.tab = key;

            console.log(this)

            let scope = this;

            if (key == 'materials') {
                Vue.set(this, 'cab_materials', this.get_cab_materials())
            }

            if (key == 'json') {
                this.set_json()
            }

        },

        show_modal_add: function (node, e) {
            this.modal_add.node = node;
            this.modal_add.show = 1;
        },

        hide_modal_add: function () {
            this.modal_add.model_id = 0;
            this.modal_add.show = 0;
            this.modal_add.node = null;
            this.modal_add.type = 'section';
            this.modal_add.name = 'Секция';
        },

        add_child: function (params) {
            let node = this.modal_add.node;
            let sel_obj = this.$root.$selected_object
            let p = copy_object(node.path)
            p.shift();

            let obj = {
                type: this.modal_add.type,
                name: this.modal_add.name
            };

            if(params.wall_type){
                obj.spec = {}
                obj.pos = {
                    x: 0,
                    y: 0,
                    z: 0
                }
                obj.pivot = {
                    x: -1,
                    y: -1,
                    z: -1
                }
                switch (params.wall_type) {
                    case 1:
                        break;
                    case 2:
                        obj.spec.orientation = 'v'
                        obj.size = {
                            "x": "s:z",
                            "y": "s:y",
                            "z": "s.z"
                        }
                        break
                    case 3:
                        obj.spec.orientation = 'v'
                        obj.pos.x = 's.x'
                        obj.pivot.x = 1;
                        obj.size = {
                            "x": "s:z",
                            "y": "s:y",
                            "z": "s.z"
                        }
                        break;
                    case 4:
                        obj.spec.orientation = 'h'
                        obj.pos.y = 's.y'
                        obj.pivot.y = 1;
                        obj.size = {
                            "x": "s:x",
                            "y": "s:z",
                            "z": "s.z"
                        }
                        break;
                    case 5:

                        obj.spec.orientation = 'h'
                        obj.size = {
                            "x": "s:x",
                            "y": "s:z",
                            "z": "s.z"
                        }
                        break;
                    case 6:
                        obj.spec.orientation = 'v'
                        obj.pos.x = 's.x / 2'
                        obj.pivot.x = 0;
                        break;
                    case 7:
                        obj.spec.orientation = 'h'
                        obj.pos.y = 's.y / 2'
                        obj.pivot.y = 0;
                        obj.size = {
                            "x": "s:x",
                            "y": "s:z",
                            "z": "s.z"
                        }
                        break;
                    case 8:
                        obj.spec.orientation = 'f'
                        obj.pos.z = 's.z / 2'
                        obj.pivot.z = 0;
                        break;
                    case 9:
                        obj.spec.orientation = 'f'
                        obj.pos.z = 's.z'
                        break;
                }
            }

            if (this.modal_add.type == 'link') {
                if (this.modal_add.model_id == 0) {
                    alert('Выберите модель')
                    return;
                }
                obj.spec = {
                    id: this.modal_add.model_id
                }
            }

            if (this.modal_add.type == 'model') {
                if (this.modal_add.model_id == 0) {
                    alert('Выберите модель')
                    return;
                }
                obj.spec = {
                    id: this.modal_add.model_id
                }
            }
            if (this.modal_add.type == 'locker2') {
                if (this.modal_add.model_id == 0) {
                    alert('Выберите модель')
                    return;
                }
                obj.spec = {
                    id: this.modal_add.model_id
                }
            }


            sel_obj.add_child_by_path(node.path.join(), obj, 0)

            this.$refs.slVueTree.insert(
                {
                    node: node,
                    placement: 'inside'
                },
                {
                    title: this.modal_add.name,
                    data: {
                        type: this.modal_add.type
                    }
                }
            )

            this.hide_modal_add();

            this.emit_update();

        },

        add_point_mouse: function(params){
            let node = this.$refs.slVueTree.getNode([0])
            ce();
            ce();
            let sel_obj = this.$root.$selected_object
            let p = copy_object(node.path)
            p.shift();
            let obj = {
                type: 'point',
                name: 'Точка привязки',
                pos: {}
            };



            let pos = params.pos;
            let pos_m = {
                x: '',
                y: '',
                z: ''
            }
            if(pos.x > 0)pos_m.x = '+ '
            if(pos.z > 0)pos_m.z = '+ '

            obj.pos.x = 's:x / 2 ' + pos_m.x + pos.x * 10
            obj.pos.z = 's:z / 2 ' + pos_m.z + pos.z * 10
            obj.pos.y = pos.y * 10

            sel_obj.add_child_by_path(node.path.join(), obj, 0)

            this.$refs.slVueTree.insert(
                {
                    node: node,
                    placement: 'inside'
                },
                {
                    title: 'Точка привязки',
                    data: {
                        type: 'point'
                    }
                }
            )


            this.emit_update();

        },

        remove_node: function (node) {
            this.$root.$selected_object.remove_by_path(node.path.join())
            Vue.set(this, 'current_params', null)
            this.$refs.slVueTree.remove([node.path]);
            this.emit_update();
        },

        copy_node: function (node) {
            ce();
            let path = node.path.join();
            let params = this.$root.$selected_object.copy_child_by_path(path, node.ind + 1)
            convert_child(params);
            this.$refs.slVueTree.insert(
                {
                    node: node,
                    placement: 'after'
                },
                params
            )
            this.emit_update();
        },
        show_modal_add_selected() {
            let node = this.$refs.slVueTree.getSelected()[0];
            this.show_modal_add(node)
            this.node_context_visible = false;
        },
        clone_selected: function () {
            let node = this.$refs.slVueTree.getSelected()[0];
            this.copy_node(node);
            this.node_context_visible = false;
        },
        copy_to_clipboard: async function () {
            let node = this.$refs.slVueTree.getSelected()[0];
            let path = node.path.join();
            let params = this.$root.$selected_object.get_child_by_path(path).params
            this.$options.clipboard = params
            if (this.$options.clipboardApi) {
                try {
                    await navigator.clipboard.writeText(JSON.stringify(params))
                } catch (e) {
                    this.$options.clipboard = params
                }
            } else {
                this.$options.clipboard = params
            }

            this.node_context_visible = false;

        },
        paste_from_clipboard: async function () {
            let node = this.$refs.slVueTree.getSelected()[0];

            let params;
            if (this.$options.clipboardApi) {
                try {
                    params = await navigator.clipboard.readText()
                    params = JSON.parse(params)
                } catch (e) {
                    params = copy_object(this.$options.clipboard);
                }
            } else {
                params = copy_object(this.$options.clipboard);
            }


            params.name += ' (Копия)';
            let index = node.children.length;
            this.insert_to_tree(node, params);


            this.$root.$selected_object.add_child_by_path(node.path.join(), params, index);
            this.node_context_visible = false;
            this.emit_update();
        },
        cut_to_clipboard: function () {
            this.copy_to_clipboard();
            this.remove_selected();
            this.node_context_visible = false;
        },
        remove_selected: function () {
            let node = this.$refs.slVueTree.getSelected()[0];
            this.remove_node(node);
            this.node_context_visible = false;
        },
        node_selected(nodes, event) {
            console.log()
            if (this.$options.obj) {
                this.$options.obj.hide_helpers()
            }
            let node = nodes;

            ce();
            ce();
            ce();
            console.log(node)

            this.$refs.slVueTree.select(node.path, false, event);

            let sel_obj = this.$root.$selected_object;
            let p = node.path.join();
            if (p === '0') {
                Vue.set(this, 'current_params', null)
                return;
            }

            console.log(node)
            console.log(p)

            let child = sel_obj.get_child_by_path(p)

            console.log(child)

            if (child.obj) {
                this.$options.obj = child.obj;
                this.$options.obj.show_helpers()
                ce();
                ce();
                ce();
            }


            delete child.params.children;

            console.log(child.params)

            Vue.set(this, 'current_params', child.params)
            // this.$root.$emit('set_item_data', 1)
        },

        nodeSelected(nodes, event) {
            // if(this.$options.obj){
            //     this.$options.obj.hide_helpers()
            // }
            // let node = nodes[0];
            // let sel_obj = this.$root.$selected_object;
            // let p = node.path.join();
            // if (p === '0') {
            //     Vue.set(this, 'current_params', null)
            //     return;
            // }
            // let child = sel_obj.get_child_by_path(p)
            // console.log(child)
            // this.$options.obj = child.obj;
            // this.$options.obj.show_helpers()
            // delete child.params.children;
            // Vue.set(this, 'current_params', child.params)
        },

        nodeToggled(node, event) {

        },

        nodeDropped(nodes, position, event) {
            this.lastEvent = `Nodes: ${nodes.map(node => node.title).join(', ')} are dropped ${position.placement} ${position.node.title}`;
            let from_path = copy_object(nodes[0].path)
            let to_path = copy_object(position.node.path)
            from_path.shift();
            to_path.shift();
            this.$root.$selected_object.change_child_position(from_path, to_path, position.placement);
            this.emit_update();
        },

        get_materials() {

        },

        insert_to_tree: function (node, params) {
            let pars = copy_object(params);
            convert_child(pars);

            let last_child = 0;
            if (node.children.length > 0) {
                last_child = node.children[node.children.length - 1];
            }
            if (last_child == 0) {
                this.$refs.slVueTree.insert(
                    {
                        node: node,
                        placement: 'inside'
                    },
                    pars
                )
            } else {
                this.$refs.slVueTree.insert(
                    {
                        node: last_child,
                        placement: 'after'
                    },
                    pars
                )
            }
        },

        reset_tree: function () {
            let sel_obj = this.$root.$selected_object;
            let tree = sel_obj.gp();
            convert_params(tree, this.root_name)
            let params = sel_obj.gp();
            console.log(params)
            Vue.set(this, 'variants', params.spec.variants)
            Vue.set(this, 'events', params.events)
            Vue.set(this, 'variables', params.variables)
            Vue.set(this, 'computed', params.computed)
            Vue.set(this, 'nodes', [tree])
        },

        set_json: function () {
            let params = this.$root.$selected_object.gp()
            delete params.size
            delete params.obj_id
            delete params.object_type
            delete params.materials
            delete params.spec.facades
            editor.set(params)
        },

        readd_materials: function () {
            let params = this.$root.$selected_object.gp()

        },

        apply_json: function () {
            let params = editor.get();
            let sel_obj = this.$root.$selected_object;
            sel_obj.set_params(params)
            sel_obj.build(true)
            this.reset_tree()
            this.emit_update();

            // toastr.success();

        },

        emit_update: function () {
            let sel_obj = this.$root.$selected_object;
            this.$root.$emit('configurator_update', sel_obj.gp())
        }
    }
})

function swap_arr_elements(arr, i1, i2) {
    arr[i1] = arr.splice(i2, 1, arr[i1])[0];
}

function is_out_of_viewport(elem) {

    // Get element's bounding
    var bounding = elem.getBoundingClientRect();

    console.log(elem)
    console.log(bounding)

    // Check if it's out of the viewport on each side
    var out = {};
    out.top = bounding.top < 0;
    out.left = bounding.left < 0;
    out.bottom = bounding.bottom > (window.innerHeight || document.documentElement.clientHeight);
    out.right = bounding.right > (window.innerWidth || document.documentElement.clientWidth);
    out.any = out.top || out.left || out.bottom || out.right;
    out.all = out.top && out.left && out.bottom && out.right;

    return out;

}


function get_tree(items, categories) {
    let data = {
        items: items,
        categories: categories
    };

    let cat_h = Object.create(null)

    data.categories.forEach(function (obj) {
        obj.categories = [];
        obj.items = [];
        cat_h[obj.id] = obj;
    })

    for (let i = 0; i < data.items.length; i++) {
        if (data.items[i].category in cat_h) {
            if (!cat_h[data.items[i].category].items) cat_h[data.items[i].category].items = [];
            cat_h[data.items[i].category].items.push(data.items[i])
        }
    }

    // let dataTree = []
    for (let i = 0; i < data.categories.length; i++) {
        if (data.categories[i].parent && data.categories[i].parent != 0) {
            if (cat_h[data.categories[i].parent])
                cat_h[data.categories[i].parent].categories.push(cat_h[data.categories[i].id])
        }
        // else{
        // dataTree.push(cat_h[data.categories[i].id])
        // }
    }


    return cat_h;
}

function treg(input) {
    let reBrackets = /{(.*?)}/g;
    let listOfText = [];
    let found;
    while (found = reBrackets.exec(input)) {
        listOfText.push(found[1]);
    }
    return listOfText
}

function convert_child(data) {

    recurse(data)

    function recurse(obj) {
        if (obj.children) {
            for (let i = 0; i < obj.children.length; i++) {
                recurse(obj.children[i])
            }
        }
        obj.title = obj.name ? obj.name : obj.type;
        if (!obj.data) obj.data = {};
        obj.data.type = obj.type;
        obj.data.visible = obj.visible;
        Object.keys(obj).forEach(function (k) {
            if (k != 'children' && k != 'title' && k != 'data') {
                delete obj[k]
            }
        })
    }
}

function convert_params(data, root_name) {

    data.name = root_name
    data.isDraggable = false;

    recurse(data)

    function recurse(obj) {
        if (obj.children) {
            for (let i = 0; i < obj.children.length; i++) {
                recurse(obj.children[i])
            }
        }
        obj.title = obj.name ? obj.name : obj.type;
        if (!obj.data) obj.data = {};
        obj.data.type = obj.type;
        obj.data.visible = obj.visible;
        Object.keys(obj).forEach(function (k) {
            if (k != 'children' && k != 'title' && k != 'data') {
                delete obj[k]
            }
        })
    }

}