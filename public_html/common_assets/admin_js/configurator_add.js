units = 10;

custom_settings = {};

accsessories_data = [];

let transparent_material = new THREE.MeshBasicMaterial({
    transparent: true,
    opacity:0,
    depthWrite: false
})
glass_mat = new THREE.MeshStandardMaterial({
    color: 0xd1e2ff,
    transparent: true,
    opacity: 0.8,
    roughness: 0,
    metalness: 0
});

project_settings = {
    "is_kitchen_model": false,
    "door_offset": 2,
    "shelve_offset": 10,
    "corpus_thickness": 16,
    "tabletop_thickness": 40,
    "bottom_modules_height": 720,
    "bottom_as_top_facade_models": true,
    "bottom_as_top_facade_materials": true,
    "bottom_as_top_corpus_materials": true,
    "cokol_as_corpus": true,
    "cokol_height": 120,
    "models": {
        "top": 1,
        "bottom": 1
    },
    "materials": {
        "top": {
            "facades": [
                0,
                0
            ],
            "corpus": [
                0,
                0
            ]
        },
        "bottom": {
            "facades": [
                0,
                0
            ],
            "corpus": [
                0,
                0
            ]
        },
        "cokol": [
            0,
            0
        ],
        "tabletop": [
            0,
            0
        ],
        "walls": [
            0
        ],
        "floor": [
            0
        ],
        "wall_panel": [
            0,
            0,
            0
        ]
    },
    "selected_materials": {
        "top": {
            "facades": 3,
            "corpus": 0
        },
        "bottom": {
            "facades": 1,
            "corpus": 0
        },
        "cokol": 0,
        "tabletop": 2,
        "wall_panel": 0,
        "walls": 0,
        "floor": 0
    },
    "handle": {
        top: {
            "orientation": "vertical",
            "lockers_position": "top",
            "selected_model": 1,
            "preferable_size": 2,
            "model": {
                "id": 1,
                "category": 2,
                "name": "Рейлинг 1",
                "icon": "/common_assets/models/handles/1/icon_1.jpg",
                "model": "/common_assets/models/handles/1/model_1.fbx",
                "size_index": 2,
                "material": {
                    "params": {
                        "color": "#dadada",
                        "roughness": "0.3",
                        "metalness": "0"
                    },
                    "add_params": {},
                    "type": "Standart"
                },
                "sizes": [
                    {
                        "width": "175",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "128",
                        "price": ""
                    },
                    {
                        "width": "207",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "160",
                        "price": ""
                    },
                    {
                        "width": "239",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "192",
                        "price": ""
                    },
                    {
                        "width": "367",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "320",
                        "price": ""
                    },
                    {
                        "width": "495",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "448",
                        "price": ""
                    },
                    {
                        "width": "527",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "480",
                        "price": ""
                    },
                    {
                        "width": "623",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "576",
                        "price": ""
                    },
                    {
                        "width": "815",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "768",
                        "price": ""
                    },
                    {
                        "width": "911",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "864",
                        "price": ""
                    }
                ]
            },
            "no_handle": false
        },
        bottom:  {
            "orientation": "vertical",
            "lockers_position": "top",
            "selected_model": 1,
            "preferable_size": 2,
            "model": {
                "id": 1,
                "category": 2,
                "name": "Рейлинг 1",
                "icon": "/common_assets/models/handles/1/icon_1.jpg",
                "model": "/common_assets/models/handles/1/model_1.fbx",
                "size_index": 2,
                "material": {
                    "params": {
                        "color": "#dadada",
                        "roughness": "0.3",
                        "metalness": "0"
                    },
                    "add_params": {},
                    "type": "Standart"
                },
                "sizes": [
                    {
                        "width": "175",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "128",
                        "price": ""
                    },
                    {
                        "width": "207",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "160",
                        "price": ""
                    },
                    {
                        "width": "239",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "192",
                        "price": ""
                    },
                    {
                        "width": "367",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "320",
                        "price": ""
                    },
                    {
                        "width": "495",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "448",
                        "price": ""
                    },
                    {
                        "width": "527",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "480",
                        "price": ""
                    },
                    {
                        "width": "623",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "576",
                        "price": ""
                    },
                    {
                        "width": "815",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "768",
                        "price": ""
                    },
                    {
                        "width": "911",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "864",
                        "price": ""
                    }
                ]
            },
            "no_handle": false
        },
        "orientation": "vertical",
        "lockers_position": "top",
        "selected_model": 1,
        "preferable_size": 2,
        "model": {
            "id": 1,
            "category": 2,
            "name": "Рейлинг 1",
            "icon": "/common_assets/models/handles/1/icon_1.jpg",
            "model": "/common_assets/models/handles/1/model_1.fbx",
            "size_index": 2,
            "material": {
                "params": {
                    "color": "#dadada",
                    "roughness": "0.3",
                    "metalness": "0"
                },
                "add_params": {},
                "type": "Standart"
            },
            "sizes": [
                {
                    "width": "175",
                    "height": "14",
                    "depth": "35",
                    "code": "",
                    "axis_size": "128",
                    "price": ""
                },
                {
                    "width": "207",
                    "height": "14",
                    "depth": "35",
                    "code": "",
                    "axis_size": "160",
                    "price": ""
                },
                {
                    "width": "239",
                    "height": "14",
                    "depth": "35",
                    "code": "",
                    "axis_size": "192",
                    "price": ""
                },
                {
                    "width": "367",
                    "height": "14",
                    "depth": "35",
                    "code": "",
                    "axis_size": "320",
                    "price": ""
                },
                {
                    "width": "495",
                    "height": "14",
                    "depth": "35",
                    "code": "",
                    "axis_size": "448",
                    "price": ""
                },
                {
                    "width": "527",
                    "height": "14",
                    "depth": "35",
                    "code": "",
                    "axis_size": "480",
                    "price": ""
                },
                {
                    "width": "623",
                    "height": "14",
                    "depth": "35",
                    "code": "",
                    "axis_size": "576",
                    "price": ""
                },
                {
                    "width": "815",
                    "height": "14",
                    "depth": "35",
                    "code": "",
                    "axis_size": "768",
                    "price": ""
                },
                {
                    "width": "911",
                    "height": "14",
                    "depth": "35",
                    "code": "",
                    "axis_size": "864",
                    "price": ""
                }
            ]
        },
        "no_handle": false
    },
    "hinges": {
        "id": 0,
        "name": "С доводчиком",
        "icon": "/common_assets/images/with_dovod.jpg",
        "door_hinges_price": 1000,
        "locker_hinges_price": 1000,
        "dovodchik": true
    },
    "wall_panel": {
        "active": false,
        "height": 560
    }
};
materials_catalog = {
    categories:[
        {
            id:0,
            name:0
        }
    ],
    items:[
        {
            "id": 0,
            "category": 0,
            "code": "RAL 1000",
            "name": "Зелёно-бежевый",
            "params": {
                "color": "#ffffff"
            }
        },
        {
            "id": 1,
            "category": 0,
            "code": "RAL 1000",
            "name": "Зелёно-бежевый",
            "params": {
                "color": "#932129"
            }
        },
        {
            "id": 2,
            "category": 0,
            "code": "RAL 1000",
            "name": "Зелёно-бежевый",
            "params": {
                "color": "#decebe"
            }
        },
        {
            "id": 3,
            "category": 0,
            "code": "RAL 1000",
            "name": "Зелёно-бежевый",
            "params": {
                "color": "#de761f"
            }
        }
    ]
};

handles_catalog = {
    "categories": [
        {
            "id": 1,
            "name": "Ручки",
            "categories": [
                {
                    "id": 2,
                    "name": "Рейлинги",
                    "parent": 1,
                    "items": [
                        {
                            "id": 9,
                            "category": 2,
                            "name": "Рейлинг 2",
                            "icon": "models/handles/9/icon_9.jpg",
                            "model": "models/handles/9/model_9.fbx",
                            "material": {
                                "params": {
                                    "color": "#ffffff",
                                    "roughness": "0.2",
                                    "metalness": "0.2"
                                },
                                "add_params": {},
                                "type": "Standart"
                            },
                            "sizes": [
                                {
                                    "width": "140",
                                    "height": "16",
                                    "depth": "28",
                                    "code": "",
                                    "axis_size": "",
                                    "price": ""
                                }
                            ]
                        },
                        {
                            "id": 1,
                            "category": 2,
                            "name": "Рейлинг 1",
                            "icon": "models/handles/1/icon_1.jpg",
                            "model": "models/handles/1/model_1.fbx",
                            "material": {
                                "params": {
                                    "color": "#dadada",
                                    "roughness": "0.3",
                                    "metalness": "0"
                                },
                                "add_params": {},
                                "type": "Standart"
                            },
                            "sizes": [
                                {
                                    "width": "175",
                                    "height": "14",
                                    "depth": "35",
                                    "code": "",
                                    "axis_size": "128",
                                    "price": ""
                                },
                                {
                                    "width": "207",
                                    "height": "14",
                                    "depth": "35",
                                    "code": "",
                                    "axis_size": "160",
                                    "price": ""
                                },
                                {
                                    "width": "239",
                                    "height": "14",
                                    "depth": "35",
                                    "code": "",
                                    "axis_size": "192",
                                    "price": ""
                                },
                                {
                                    "width": "367",
                                    "height": "14",
                                    "depth": "35",
                                    "code": "",
                                    "axis_size": "320",
                                    "price": ""
                                },
                                {
                                    "width": "495",
                                    "height": "14",
                                    "depth": "35",
                                    "code": "",
                                    "axis_size": "448",
                                    "price": ""
                                },
                                {
                                    "width": "527",
                                    "height": "14",
                                    "depth": "35",
                                    "code": "",
                                    "axis_size": "480",
                                    "price": ""
                                },
                                {
                                    "width": "623",
                                    "height": "14",
                                    "depth": "35",
                                    "code": "",
                                    "axis_size": "576",
                                    "price": ""
                                },
                                {
                                    "width": "815",
                                    "height": "14",
                                    "depth": "35",
                                    "code": "",
                                    "axis_size": "768",
                                    "price": ""
                                },
                                {
                                    "width": "911",
                                    "height": "14",
                                    "depth": "35",
                                    "code": "",
                                    "axis_size": "864",
                                    "price": ""
                                }
                            ]
                        }
                    ]
                },
                {
                    "id": 3,
                    "name": "Скобы",
                    "parent": 1,
                    "items": [
                        {
                            "id": 5,
                            "category": 3,
                            "name": "Скоба 4",
                            "icon": "models/handles/5/icon_5.jpg",
                            "model": "models/handles/5/model_5.fbx",
                            "material": {
                                "params": {
                                    "color": "#ffffff",
                                    "roughness": "0.2",
                                    "metalness": "0.2"
                                },
                                "add_params": {},
                                "type": "Standart"
                            },
                            "sizes": [
                                {
                                    "width": "204",
                                    "height": "15",
                                    "depth": "35",
                                    "code": "",
                                    "axis_size": "",
                                    "price": ""
                                }
                            ]
                        },
                        {
                            "id": 4,
                            "category": 3,
                            "name": "Скоба 3",
                            "icon": "models/handles/4/icon_4.jpg",
                            "model": "models/handles/4/model_4.fbx",
                            "material": {
                                "params": {
                                    "color": "#ffffff",
                                    "roughness": "0.2",
                                    "metalness": "0.2"
                                },
                                "add_params": {},
                                "type": "Standart"
                            },
                            "sizes": [
                                {
                                    "width": "214",
                                    "height": "16",
                                    "depth": "18",
                                    "code": "",
                                    "axis_size": "",
                                    "price": ""
                                }
                            ]
                        },
                        {
                            "id": 3,
                            "category": 3,
                            "name": "Скоба 2",
                            "icon": "models/handles/3/icon_3.jpg",
                            "model": "models/handles/3/model_3.fbx",
                            "material": {
                                "params": {
                                    "color": "#ffffff",
                                    "roughness": "0.2",
                                    "metalness": "0.2"
                                },
                                "add_params": {},
                                "type": "Standart"
                            },
                            "sizes": [
                                {
                                    "width": "179",
                                    "height": "19",
                                    "depth": "20",
                                    "code": "",
                                    "axis_size": "",
                                    "price": ""
                                }
                            ]
                        },
                        {
                            "id": 2,
                            "category": 3,
                            "name": "Скоба 1",
                            "icon": "models/handles/2/icon_2.jpg",
                            "model": "models/handles/2/model_2.fbx",
                            "material": {
                                "params": {
                                    "color": "#dadada",
                                    "roughness": "0.3",
                                    "metalness": "0"
                                },
                                "add_params": {},
                                "type": "Standart"
                            },
                            "sizes": [
                                {
                                    "width": "76",
                                    "height": "12",
                                    "depth": "24",
                                    "code": "",
                                    "axis_size": "64",
                                    "price": ""
                                },
                                {
                                    "width": "108",
                                    "height": "12",
                                    "depth": "24",
                                    "code": "",
                                    "axis_size": "96",
                                    "price": ""
                                },
                                {
                                    "width": "140",
                                    "height": "12",
                                    "depth": "24",
                                    "code": "",
                                    "axis_size": "128",
                                    "price": ""
                                },
                                {
                                    "width": "239",
                                    "height": "12",
                                    "depth": "24",
                                    "code": "",
                                    "axis_size": "215",
                                    "price": ""
                                },
                                {
                                    "width": "292",
                                    "height": "12",
                                    "depth": "24",
                                    "code": "",
                                    "axis_size": "280",
                                    "price": ""
                                },
                                {
                                    "width": "332",
                                    "height": "12",
                                    "depth": "24",
                                    "code": "",
                                    "axis_size": "320",
                                    "price": ""
                                }
                            ]
                        }
                    ]
                },
                {
                    "id": 4,
                    "name": "Кнопки",
                    "parent": 1,
                    "items": [
                        {
                            "id": 7,
                            "category": 4,
                            "name": "Кнопка 2",
                            "icon": "models/handles/7/icon_7.jpg",
                            "model": "models/handles/7/model_7.fbx",
                            "material": {
                                "params": {
                                    "color": "#ffffff",
                                    "roughness": "0.2",
                                    "metalness": "0.2"
                                },
                                "add_params": {},
                                "type": "Standart"
                            },
                            "sizes": [
                                {
                                    "width": "38",
                                    "height": "38",
                                    "depth": "20",
                                    "code": "",
                                    "axis_size": "",
                                    "price": ""
                                }
                            ]
                        },
                        {
                            "id": 6,
                            "category": 4,
                            "name": "Кнопка 1",
                            "icon": "models/handles/6/icon_6.jpg",
                            "model": "models/handles/6/model_6.fbx",
                            "material": {
                                "params": {
                                    "color": "#ffffff",
                                    "roughness": "0.2",
                                    "metalness": "0.2"
                                },
                                "add_params": {},
                                "type": "Standart"
                            },
                            "sizes": [
                                {
                                    "width": "36",
                                    "height": "36",
                                    "depth": "34",
                                    "code": "",
                                    "axis_size": "",
                                    "price": ""
                                }
                            ]
                        }
                    ]
                },
                {
                    "id": 5,
                    "name": "Классика",
                    "parent": 1,
                    "items": [
                        {
                            "id": 15,
                            "category": 5,
                            "name": "Классика серебро",
                            "icon": "/common_assets/models/handles/16/icon_16.jpg",
                            "model": "/common_assets/models/handles/16/model_16.fbx",
                            "material": {
                                "params": {
                                    "color": "#ffffff",
                                    "roughness": "0.4",
                                    "metalness": "0",
                                    "map": "/common_assets/models/handles/16/map_16.jpg"
                                },
                                "add_params": {
                                    "real_width": "512",
                                    "real_height": "512",
                                    "stretch_width": "1",
                                    "stretch_height": "1",
                                    "wrapping": "mirror"
                                },
                                "type": "Standart"
                            },
                            "sizes": [
                                {
                                    "width": "126",
                                    "height": "37",
                                    "depth": "26",
                                    "code": "",
                                    "axis_size": "",
                                    "price": ""
                                }
                            ]
                        },
                        {
                            "id": 14,
                            "category": 5,
                            "name": "Классика серебро черное",
                            "icon": "/common_assets/models/handles/15/icon_15.jpg",
                            "model": "/common_assets/models/handles/15/model_15.fbx",
                            "material": {
                                "params": {
                                    "color": "#ffffff",
                                    "roughness": "0.4",
                                    "metalness": "0",
                                    "map": "/common_assets/models/handles/15/map_15.jpg"
                                },
                                "add_params": {
                                    "real_width": "512",
                                    "real_height": "512",
                                    "stretch_width": "1",
                                    "stretch_height": "1",
                                    "wrapping": "mirror"
                                },
                                "type": "Standart"
                            },
                            "sizes": [
                                {
                                    "width": "126",
                                    "height": "37",
                                    "depth": "26",
                                    "code": "",
                                    "axis_size": "",
                                    "price": ""
                                }
                            ]
                        },
                        {
                            "id": 13,
                            "category": 5,
                            "name": "Классика бронза",
                            "icon": "/common_assets/models/handles/14/icon_14.jpg",
                            "model": "/common_assets/models/handles/14/model_14.fbx",
                            "material": {
                                "params": {
                                    "color": "#ffffff",
                                    "roughness": "0.4",
                                    "metalness": "0",
                                    "map": "/common_assets/models/handles/14/map_14.jpg"
                                },
                                "add_params": {
                                    "real_width": "512",
                                    "real_height": "512",
                                    "stretch_width": "1",
                                    "stretch_height": "1",
                                    "wrapping": "mirror"
                                },
                                "type": "Standart"
                            },
                            "sizes": [
                                {
                                    "width": "126",
                                    "height": "37",
                                    "depth": "26",
                                    "code": "",
                                    "axis_size": "",
                                    "price": ""
                                }
                            ]
                        },
                        {
                            "id": 12,
                            "category": 5,
                            "name": "Классика кнопка серебро",
                            "icon": "/common_assets/models/handles/13/icon_13.jpg",
                            "model": "/common_assets/models/handles/13/model_13.fbx",
                            "material": {
                                "params": {
                                    "color": "#ffffff",
                                    "roughness": "0.4",
                                    "metalness": "0.1",
                                    "map": "/common_assets/models/handles/13/map_13.jpg"
                                },
                                "add_params": {
                                    "real_width": "512",
                                    "real_height": "512",
                                    "stretch_width": "1",
                                    "stretch_height": "1",
                                    "wrapping": "mirror"
                                },
                                "type": "Standart"
                            },
                            "sizes": [
                                {
                                    "width": "30",
                                    "height": "23",
                                    "depth": "30",
                                    "code": "",
                                    "axis_size": "",
                                    "price": ""
                                }
                            ]
                        },
                        {
                            "id": 11,
                            "category": 5,
                            "name": "Классика кнопка серебро черное",
                            "icon": "/common_assets/models/handles/12/icon_12.jpg",
                            "model": "/common_assets/models/handles/12/model_12.fbx",
                            "material": {
                                "params": {
                                    "color": "#ffffff",
                                    "roughness": "0.4",
                                    "metalness": "0.1",
                                    "map": "/common_assets/models/handles/12/map_12.jpg"
                                },
                                "add_params": {
                                    "real_width": "512",
                                    "real_height": "512",
                                    "stretch_width": "1",
                                    "stretch_height": "1",
                                    "wrapping": "mirror"
                                },
                                "type": "Standart"
                            },
                            "sizes": [
                                {
                                    "width": "30",
                                    "height": "23",
                                    "depth": "30",
                                    "code": "",
                                    "axis_size": "",
                                    "price": ""
                                }
                            ]
                        },
                        {
                            "id": 10,
                            "category": 5,
                            "name": "Классика кнопка бронза",
                            "icon": "/common_assets/models/handles/11/icon_11.jpg",
                            "model": "/common_assets/models/handles/11/model_11.fbx",
                            "material": {
                                "params": {
                                    "color": "#ffffff",
                                    "roughness": "0.4",
                                    "metalness": "0.1",
                                    "map": "/common_assets/models/handles/11/map_11.jpg"
                                },
                                "add_params": {
                                    "real_width": "512",
                                    "real_height": "512",
                                    "stretch_width": "1",
                                    "stretch_height": "1",
                                    "wrapping": "mirror"
                                },
                                "type": "Standart"
                            },
                            "sizes": [
                                {
                                    "width": "30",
                                    "height": "23",
                                    "depth": "30",
                                    "code": "",
                                    "axis_size": "",
                                    "price": ""
                                }
                            ]
                        },
                        {
                            "id": 8,
                            "category": 5,
                            "name": "Классика 1",
                            "icon": "models/handles/8/icon_8.jpg",
                            "model": "models/handles/8/model_8.fbx",
                            "material": {
                                "params": {
                                    "color": "#d0cd6e",
                                    "roughness": "0.2",
                                    "metalness": "0.2"
                                },
                                "add_params": {},
                                "type": "Standart"
                            },
                            "sizes": [
                                {
                                    "width": "116",
                                    "height": "19",
                                    "depth": "28",
                                    "code": "",
                                    "axis_size": "",
                                    "price": ""
                                }
                            ]
                        }
                    ]
                }
            ]
        },
        {
            "id": 2,
            "name": "Рейлинги",
            "parent": 1,
            "items": [
                {
                    "id": 9,
                    "category": 2,
                    "name": "Рейлинг 2",
                    "icon": "models/handles/9/icon_9.jpg",
                    "model": "models/handles/9/model_9.fbx",
                    "material": {
                        "params": {
                            "color": "#ffffff",
                            "roughness": "0.2",
                            "metalness": "0.2"
                        },
                        "add_params": {},
                        "type": "Standart"
                    },
                    "sizes": [
                        {
                            "width": "140",
                            "height": "16",
                            "depth": "28",
                            "code": "",
                            "axis_size": "",
                            "price": ""
                        }
                    ]
                },
                {
                    "id": 1,
                    "category": 2,
                    "name": "Рейлинг 1",
                    "icon": "models/handles/1/icon_1.jpg",
                    "model": "models/handles/1/model_1.fbx",
                    "material": {
                        "params": {
                            "color": "#dadada",
                            "roughness": "0.3",
                            "metalness": "0"
                        },
                        "add_params": {},
                        "type": "Standart"
                    },
                    "sizes": [
                        {
                            "width": "175",
                            "height": "14",
                            "depth": "35",
                            "code": "",
                            "axis_size": "128",
                            "price": ""
                        },
                        {
                            "width": "207",
                            "height": "14",
                            "depth": "35",
                            "code": "",
                            "axis_size": "160",
                            "price": ""
                        },
                        {
                            "width": "239",
                            "height": "14",
                            "depth": "35",
                            "code": "",
                            "axis_size": "192",
                            "price": ""
                        },
                        {
                            "width": "367",
                            "height": "14",
                            "depth": "35",
                            "code": "",
                            "axis_size": "320",
                            "price": ""
                        },
                        {
                            "width": "495",
                            "height": "14",
                            "depth": "35",
                            "code": "",
                            "axis_size": "448",
                            "price": ""
                        },
                        {
                            "width": "527",
                            "height": "14",
                            "depth": "35",
                            "code": "",
                            "axis_size": "480",
                            "price": ""
                        },
                        {
                            "width": "623",
                            "height": "14",
                            "depth": "35",
                            "code": "",
                            "axis_size": "576",
                            "price": ""
                        },
                        {
                            "width": "815",
                            "height": "14",
                            "depth": "35",
                            "code": "",
                            "axis_size": "768",
                            "price": ""
                        },
                        {
                            "width": "911",
                            "height": "14",
                            "depth": "35",
                            "code": "",
                            "axis_size": "864",
                            "price": ""
                        }
                    ]
                }
            ]
        },
        {
            "id": 3,
            "name": "Скобы",
            "parent": 1,
            "items": [
                {
                    "id": 5,
                    "category": 3,
                    "name": "Скоба 4",
                    "icon": "models/handles/5/icon_5.jpg",
                    "model": "models/handles/5/model_5.fbx",
                    "material": {
                        "params": {
                            "color": "#ffffff",
                            "roughness": "0.2",
                            "metalness": "0.2"
                        },
                        "add_params": {},
                        "type": "Standart"
                    },
                    "sizes": [
                        {
                            "width": "204",
                            "height": "15",
                            "depth": "35",
                            "code": "",
                            "axis_size": "",
                            "price": ""
                        }
                    ]
                },
                {
                    "id": 4,
                    "category": 3,
                    "name": "Скоба 3",
                    "icon": "models/handles/4/icon_4.jpg",
                    "model": "models/handles/4/model_4.fbx",
                    "material": {
                        "params": {
                            "color": "#ffffff",
                            "roughness": "0.2",
                            "metalness": "0.2"
                        },
                        "add_params": {},
                        "type": "Standart"
                    },
                    "sizes": [
                        {
                            "width": "214",
                            "height": "16",
                            "depth": "18",
                            "code": "",
                            "axis_size": "",
                            "price": ""
                        }
                    ]
                },
                {
                    "id": 3,
                    "category": 3,
                    "name": "Скоба 2",
                    "icon": "models/handles/3/icon_3.jpg",
                    "model": "models/handles/3/model_3.fbx",
                    "material": {
                        "params": {
                            "color": "#ffffff",
                            "roughness": "0.2",
                            "metalness": "0.2"
                        },
                        "add_params": {},
                        "type": "Standart"
                    },
                    "sizes": [
                        {
                            "width": "179",
                            "height": "19",
                            "depth": "20",
                            "code": "",
                            "axis_size": "",
                            "price": ""
                        }
                    ]
                },
                {
                    "id": 2,
                    "category": 3,
                    "name": "Скоба 1",
                    "icon": "models/handles/2/icon_2.jpg",
                    "model": "models/handles/2/model_2.fbx",
                    "material": {
                        "params": {
                            "color": "#dadada",
                            "roughness": "0.3",
                            "metalness": "0"
                        },
                        "add_params": {},
                        "type": "Standart"
                    },
                    "sizes": [
                        {
                            "width": "76",
                            "height": "12",
                            "depth": "24",
                            "code": "",
                            "axis_size": "64",
                            "price": ""
                        },
                        {
                            "width": "108",
                            "height": "12",
                            "depth": "24",
                            "code": "",
                            "axis_size": "96",
                            "price": ""
                        },
                        {
                            "width": "140",
                            "height": "12",
                            "depth": "24",
                            "code": "",
                            "axis_size": "128",
                            "price": ""
                        },
                        {
                            "width": "239",
                            "height": "12",
                            "depth": "24",
                            "code": "",
                            "axis_size": "215",
                            "price": ""
                        },
                        {
                            "width": "292",
                            "height": "12",
                            "depth": "24",
                            "code": "",
                            "axis_size": "280",
                            "price": ""
                        },
                        {
                            "width": "332",
                            "height": "12",
                            "depth": "24",
                            "code": "",
                            "axis_size": "320",
                            "price": ""
                        }
                    ]
                }
            ]
        },
        {
            "id": 4,
            "name": "Кнопки",
            "parent": 1,
            "items": [
                {
                    "id": 7,
                    "category": 4,
                    "name": "Кнопка 2",
                    "icon": "models/handles/7/icon_7.jpg",
                    "model": "models/handles/7/model_7.fbx",
                    "material": {
                        "params": {
                            "color": "#ffffff",
                            "roughness": "0.2",
                            "metalness": "0.2"
                        },
                        "add_params": {},
                        "type": "Standart"
                    },
                    "sizes": [
                        {
                            "width": "38",
                            "height": "38",
                            "depth": "20",
                            "code": "",
                            "axis_size": "",
                            "price": ""
                        }
                    ]
                },
                {
                    "id": 6,
                    "category": 4,
                    "name": "Кнопка 1",
                    "icon": "models/handles/6/icon_6.jpg",
                    "model": "models/handles/6/model_6.fbx",
                    "material": {
                        "params": {
                            "color": "#ffffff",
                            "roughness": "0.2",
                            "metalness": "0.2"
                        },
                        "add_params": {},
                        "type": "Standart"
                    },
                    "sizes": [
                        {
                            "width": "36",
                            "height": "36",
                            "depth": "34",
                            "code": "",
                            "axis_size": "",
                            "price": ""
                        }
                    ]
                }
            ]
        },
        {
            "id": 5,
            "name": "Классика",
            "parent": 1,
            "items": [
                {
                    "id": 15,
                    "category": 5,
                    "name": "Классика серебро",
                    "icon": "/common_assets/models/handles/16/icon_16.jpg",
                    "model": "/common_assets/models/handles/16/model_16.fbx",
                    "material": {
                        "params": {
                            "color": "#ffffff",
                            "roughness": "0.4",
                            "metalness": "0",
                            "map": "/common_assets/models/handles/16/map_16.jpg"
                        },
                        "add_params": {
                            "real_width": "512",
                            "real_height": "512",
                            "stretch_width": "1",
                            "stretch_height": "1",
                            "wrapping": "mirror"
                        },
                        "type": "Standart"
                    },
                    "sizes": [
                        {
                            "width": "126",
                            "height": "37",
                            "depth": "26",
                            "code": "",
                            "axis_size": "",
                            "price": ""
                        }
                    ]
                },
                {
                    "id": 14,
                    "category": 5,
                    "name": "Классика серебро черное",
                    "icon": "/common_assets/models/handles/15/icon_15.jpg",
                    "model": "/common_assets/models/handles/15/model_15.fbx",
                    "material": {
                        "params": {
                            "color": "#ffffff",
                            "roughness": "0.4",
                            "metalness": "0",
                            "map": "/common_assets/models/handles/15/map_15.jpg"
                        },
                        "add_params": {
                            "real_width": "512",
                            "real_height": "512",
                            "stretch_width": "1",
                            "stretch_height": "1",
                            "wrapping": "mirror"
                        },
                        "type": "Standart"
                    },
                    "sizes": [
                        {
                            "width": "126",
                            "height": "37",
                            "depth": "26",
                            "code": "",
                            "axis_size": "",
                            "price": ""
                        }
                    ]
                },
                {
                    "id": 13,
                    "category": 5,
                    "name": "Классика бронза",
                    "icon": "/common_assets/models/handles/14/icon_14.jpg",
                    "model": "/common_assets/models/handles/14/model_14.fbx",
                    "material": {
                        "params": {
                            "color": "#ffffff",
                            "roughness": "0.4",
                            "metalness": "0",
                            "map": "/common_assets/models/handles/14/map_14.jpg"
                        },
                        "add_params": {
                            "real_width": "512",
                            "real_height": "512",
                            "stretch_width": "1",
                            "stretch_height": "1",
                            "wrapping": "mirror"
                        },
                        "type": "Standart"
                    },
                    "sizes": [
                        {
                            "width": "126",
                            "height": "37",
                            "depth": "26",
                            "code": "",
                            "axis_size": "",
                            "price": ""
                        }
                    ]
                },
                {
                    "id": 12,
                    "category": 5,
                    "name": "Классика кнопка серебро",
                    "icon": "/common_assets/models/handles/13/icon_13.jpg",
                    "model": "/common_assets/models/handles/13/model_13.fbx",
                    "material": {
                        "params": {
                            "color": "#ffffff",
                            "roughness": "0.4",
                            "metalness": "0.1",
                            "map": "/common_assets/models/handles/13/map_13.jpg"
                        },
                        "add_params": {
                            "real_width": "512",
                            "real_height": "512",
                            "stretch_width": "1",
                            "stretch_height": "1",
                            "wrapping": "mirror"
                        },
                        "type": "Standart"
                    },
                    "sizes": [
                        {
                            "width": "30",
                            "height": "23",
                            "depth": "30",
                            "code": "",
                            "axis_size": "",
                            "price": ""
                        }
                    ]
                },
                {
                    "id": 11,
                    "category": 5,
                    "name": "Классика кнопка серебро черное",
                    "icon": "/common_assets/models/handles/12/icon_12.jpg",
                    "model": "/common_assets/models/handles/12/model_12.fbx",
                    "material": {
                        "params": {
                            "color": "#ffffff",
                            "roughness": "0.4",
                            "metalness": "0.1",
                            "map": "/common_assets/models/handles/12/map_12.jpg"
                        },
                        "add_params": {
                            "real_width": "512",
                            "real_height": "512",
                            "stretch_width": "1",
                            "stretch_height": "1",
                            "wrapping": "mirror"
                        },
                        "type": "Standart"
                    },
                    "sizes": [
                        {
                            "width": "30",
                            "height": "23",
                            "depth": "30",
                            "code": "",
                            "axis_size": "",
                            "price": ""
                        }
                    ]
                },
                {
                    "id": 10,
                    "category": 5,
                    "name": "Классика кнопка бронза",
                    "icon": "/common_assets/models/handles/11/icon_11.jpg",
                    "model": "/common_assets/models/handles/11/model_11.fbx",
                    "material": {
                        "params": {
                            "color": "#ffffff",
                            "roughness": "0.4",
                            "metalness": "0.1",
                            "map": "/common_assets/models/handles/11/map_11.jpg"
                        },
                        "add_params": {
                            "real_width": "512",
                            "real_height": "512",
                            "stretch_width": "1",
                            "stretch_height": "1",
                            "wrapping": "mirror"
                        },
                        "type": "Standart"
                    },
                    "sizes": [
                        {
                            "width": "30",
                            "height": "23",
                            "depth": "30",
                            "code": "",
                            "axis_size": "",
                            "price": ""
                        }
                    ]
                },
                {
                    "id": 8,
                    "category": 5,
                    "name": "Классика 1",
                    "icon": "models/handles/8/icon_8.jpg",
                    "model": "models/handles/8/model_8.fbx",
                    "material": {
                        "params": {
                            "color": "#d0cd6e",
                            "roughness": "0.2",
                            "metalness": "0.2"
                        },
                        "add_params": {},
                        "type": "Standart"
                    },
                    "sizes": [
                        {
                            "width": "116",
                            "height": "19",
                            "depth": "28",
                            "code": "",
                            "axis_size": "",
                            "price": ""
                        }
                    ]
                }
            ]
        }
    ],
    "items": [
        {
            "id": 15,
            "category": 5,
            "name": "Классика серебро",
            "icon": "/common_assets/models/handles/16/icon_16.jpg",
            "model": "/common_assets/models/handles/16/model_16.fbx",
            "material": {
                "params": {
                    "color": "#ffffff",
                    "roughness": "0.4",
                    "metalness": "0",
                    "map": "/common_assets/models/handles/16/map_16.jpg"
                },
                "add_params": {
                    "real_width": "512",
                    "real_height": "512",
                    "stretch_width": "1",
                    "stretch_height": "1",
                    "wrapping": "mirror"
                },
                "type": "Standart"
            },
            "sizes": [
                {
                    "width": "126",
                    "height": "37",
                    "depth": "26",
                    "code": "",
                    "axis_size": "",
                    "price": ""
                }
            ]
        },
        {
            "id": 14,
            "category": 5,
            "name": "Классика серебро черное",
            "icon": "/common_assets/models/handles/15/icon_15.jpg",
            "model": "/common_assets/models/handles/15/model_15.fbx",
            "material": {
                "params": {
                    "color": "#ffffff",
                    "roughness": "0.4",
                    "metalness": "0",
                    "map": "/common_assets/models/handles/15/map_15.jpg"
                },
                "add_params": {
                    "real_width": "512",
                    "real_height": "512",
                    "stretch_width": "1",
                    "stretch_height": "1",
                    "wrapping": "mirror"
                },
                "type": "Standart"
            },
            "sizes": [
                {
                    "width": "126",
                    "height": "37",
                    "depth": "26",
                    "code": "",
                    "axis_size": "",
                    "price": ""
                }
            ]
        },
        {
            "id": 13,
            "category": 5,
            "name": "Классика бронза",
            "icon": "/common_assets/models/handles/14/icon_14.jpg",
            "model": "/common_assets/models/handles/14/model_14.fbx",
            "material": {
                "params": {
                    "color": "#ffffff",
                    "roughness": "0.4",
                    "metalness": "0",
                    "map": "/common_assets/models/handles/14/map_14.jpg"
                },
                "add_params": {
                    "real_width": "512",
                    "real_height": "512",
                    "stretch_width": "1",
                    "stretch_height": "1",
                    "wrapping": "mirror"
                },
                "type": "Standart"
            },
            "sizes": [
                {
                    "width": "126",
                    "height": "37",
                    "depth": "26",
                    "code": "",
                    "axis_size": "",
                    "price": ""
                }
            ]
        },
        {
            "id": 12,
            "category": 5,
            "name": "Классика кнопка серебро",
            "icon": "/common_assets/models/handles/13/icon_13.jpg",
            "model": "/common_assets/models/handles/13/model_13.fbx",
            "material": {
                "params": {
                    "color": "#ffffff",
                    "roughness": "0.4",
                    "metalness": "0.1",
                    "map": "/common_assets/models/handles/13/map_13.jpg"
                },
                "add_params": {
                    "real_width": "512",
                    "real_height": "512",
                    "stretch_width": "1",
                    "stretch_height": "1",
                    "wrapping": "mirror"
                },
                "type": "Standart"
            },
            "sizes": [
                {
                    "width": "30",
                    "height": "23",
                    "depth": "30",
                    "code": "",
                    "axis_size": "",
                    "price": ""
                }
            ]
        },
        {
            "id": 11,
            "category": 5,
            "name": "Классика кнопка серебро черное",
            "icon": "/common_assets/models/handles/12/icon_12.jpg",
            "model": "/common_assets/models/handles/12/model_12.fbx",
            "material": {
                "params": {
                    "color": "#ffffff",
                    "roughness": "0.4",
                    "metalness": "0.1",
                    "map": "/common_assets/models/handles/12/map_12.jpg"
                },
                "add_params": {
                    "real_width": "512",
                    "real_height": "512",
                    "stretch_width": "1",
                    "stretch_height": "1",
                    "wrapping": "mirror"
                },
                "type": "Standart"
            },
            "sizes": [
                {
                    "width": "30",
                    "height": "23",
                    "depth": "30",
                    "code": "",
                    "axis_size": "",
                    "price": ""
                }
            ]
        },
        {
            "id": 10,
            "category": 5,
            "name": "Классика кнопка бронза",
            "icon": "/common_assets/models/handles/11/icon_11.jpg",
            "model": "/common_assets/models/handles/11/model_11.fbx",
            "material": {
                "params": {
                    "color": "#ffffff",
                    "roughness": "0.4",
                    "metalness": "0.1",
                    "map": "/common_assets/models/handles/11/map_11.jpg"
                },
                "add_params": {
                    "real_width": "512",
                    "real_height": "512",
                    "stretch_width": "1",
                    "stretch_height": "1",
                    "wrapping": "mirror"
                },
                "type": "Standart"
            },
            "sizes": [
                {
                    "width": "30",
                    "height": "23",
                    "depth": "30",
                    "code": "",
                    "axis_size": "",
                    "price": ""
                }
            ]
        },
        {
            "id": 9,
            "category": 2,
            "name": "Рейлинг 2",
            "icon": "models/handles/9/icon_9.jpg",
            "model": "models/handles/9/model_9.fbx",
            "material": {
                "params": {
                    "color": "#ffffff",
                    "roughness": "0.2",
                    "metalness": "0.2"
                },
                "add_params": {},
                "type": "Standart"
            },
            "sizes": [
                {
                    "width": "140",
                    "height": "16",
                    "depth": "28",
                    "code": "",
                    "axis_size": "",
                    "price": ""
                }
            ]
        },
        {
            "id": 8,
            "category": 5,
            "name": "Классика 1",
            "icon": "models/handles/8/icon_8.jpg",
            "model": "models/handles/8/model_8.fbx",
            "material": {
                "params": {
                    "color": "#d0cd6e",
                    "roughness": "0.2",
                    "metalness": "0.2"
                },
                "add_params": {},
                "type": "Standart"
            },
            "sizes": [
                {
                    "width": "116",
                    "height": "19",
                    "depth": "28",
                    "code": "",
                    "axis_size": "",
                    "price": ""
                }
            ]
        },
        {
            "id": 7,
            "category": 4,
            "name": "Кнопка 2",
            "icon": "models/handles/7/icon_7.jpg",
            "model": "models/handles/7/model_7.fbx",
            "material": {
                "params": {
                    "color": "#ffffff",
                    "roughness": "0.2",
                    "metalness": "0.2"
                },
                "add_params": {},
                "type": "Standart"
            },
            "sizes": [
                {
                    "width": "38",
                    "height": "38",
                    "depth": "20",
                    "code": "",
                    "axis_size": "",
                    "price": ""
                }
            ]
        },
        {
            "id": 6,
            "category": 4,
            "name": "Кнопка 1",
            "icon": "models/handles/6/icon_6.jpg",
            "model": "models/handles/6/model_6.fbx",
            "material": {
                "params": {
                    "color": "#ffffff",
                    "roughness": "0.2",
                    "metalness": "0.2"
                },
                "add_params": {},
                "type": "Standart"
            },
            "sizes": [
                {
                    "width": "36",
                    "height": "36",
                    "depth": "34",
                    "code": "",
                    "axis_size": "",
                    "price": ""
                }
            ]
        },
        {
            "id": 5,
            "category": 3,
            "name": "Скоба 4",
            "icon": "models/handles/5/icon_5.jpg",
            "model": "models/handles/5/model_5.fbx",
            "material": {
                "params": {
                    "color": "#ffffff",
                    "roughness": "0.2",
                    "metalness": "0.2"
                },
                "add_params": {},
                "type": "Standart"
            },
            "sizes": [
                {
                    "width": "204",
                    "height": "15",
                    "depth": "35",
                    "code": "",
                    "axis_size": "",
                    "price": ""
                }
            ]
        },
        {
            "id": 4,
            "category": 3,
            "name": "Скоба 3",
            "icon": "models/handles/4/icon_4.jpg",
            "model": "models/handles/4/model_4.fbx",
            "material": {
                "params": {
                    "color": "#ffffff",
                    "roughness": "0.2",
                    "metalness": "0.2"
                },
                "add_params": {},
                "type": "Standart"
            },
            "sizes": [
                {
                    "width": "214",
                    "height": "16",
                    "depth": "18",
                    "code": "",
                    "axis_size": "",
                    "price": ""
                }
            ]
        },
        {
            "id": 3,
            "category": 3,
            "name": "Скоба 2",
            "icon": "models/handles/3/icon_3.jpg",
            "model": "models/handles/3/model_3.fbx",
            "material": {
                "params": {
                    "color": "#ffffff",
                    "roughness": "0.2",
                    "metalness": "0.2"
                },
                "add_params": {},
                "type": "Standart"
            },
            "sizes": [
                {
                    "width": "179",
                    "height": "19",
                    "depth": "20",
                    "code": "",
                    "axis_size": "",
                    "price": ""
                }
            ]
        },
        {
            "id": 2,
            "category": 3,
            "name": "Скоба 1",
            "icon": "models/handles/2/icon_2.jpg",
            "model": "models/handles/2/model_2.fbx",
            "material": {
                "params": {
                    "color": "#dadada",
                    "roughness": "0.3",
                    "metalness": "0"
                },
                "add_params": {},
                "type": "Standart"
            },
            "sizes": [
                {
                    "width": "76",
                    "height": "12",
                    "depth": "24",
                    "code": "",
                    "axis_size": "64",
                    "price": ""
                },
                {
                    "width": "108",
                    "height": "12",
                    "depth": "24",
                    "code": "",
                    "axis_size": "96",
                    "price": ""
                },
                {
                    "width": "140",
                    "height": "12",
                    "depth": "24",
                    "code": "",
                    "axis_size": "128",
                    "price": ""
                },
                {
                    "width": "239",
                    "height": "12",
                    "depth": "24",
                    "code": "",
                    "axis_size": "215",
                    "price": ""
                },
                {
                    "width": "292",
                    "height": "12",
                    "depth": "24",
                    "code": "",
                    "axis_size": "280",
                    "price": ""
                },
                {
                    "width": "332",
                    "height": "12",
                    "depth": "24",
                    "code": "",
                    "axis_size": "320",
                    "price": ""
                }
            ]
        },
        {
            "id": 1,
            "category": 2,
            "name": "Рейлинг 1",
            "icon": "models/handles/1/icon_1.jpg",
            "model": "models/handles/1/model_1.fbx",
            "material": {
                "params": {
                    "color": "#dadada",
                    "roughness": "0.3",
                    "metalness": "0"
                },
                "add_params": {},
                "type": "Standart"
            },
            "sizes": [
                {
                    "width": "175",
                    "height": "14",
                    "depth": "35",
                    "code": "",
                    "axis_size": "128",
                    "price": ""
                },
                {
                    "width": "207",
                    "height": "14",
                    "depth": "35",
                    "code": "",
                    "axis_size": "160",
                    "price": ""
                },
                {
                    "width": "239",
                    "height": "14",
                    "depth": "35",
                    "code": "",
                    "axis_size": "192",
                    "price": ""
                },
                {
                    "width": "367",
                    "height": "14",
                    "depth": "35",
                    "code": "",
                    "axis_size": "320",
                    "price": ""
                },
                {
                    "width": "495",
                    "height": "14",
                    "depth": "35",
                    "code": "",
                    "axis_size": "448",
                    "price": ""
                },
                {
                    "width": "527",
                    "height": "14",
                    "depth": "35",
                    "code": "",
                    "axis_size": "480",
                    "price": ""
                },
                {
                    "width": "623",
                    "height": "14",
                    "depth": "35",
                    "code": "",
                    "axis_size": "576",
                    "price": ""
                },
                {
                    "width": "815",
                    "height": "14",
                    "depth": "35",
                    "code": "",
                    "axis_size": "768",
                    "price": ""
                },
                {
                    "width": "911",
                    "height": "14",
                    "depth": "35",
                    "code": "",
                    "axis_size": "864",
                    "price": ""
                }
            ]
        }
    ]
};

let materials_lib = {
    tree: '',
    items: {
        0:{
            id:0,
            category: 0,
            code:'',
            name: 'Материал в базе не найден',
            params:{
                color: '#ffffff'
            },
            add_params:{

            }
        }
    },
    categories: {
        0:{
            id:0,
            parent: 0,
            code:'',
            name: 'Категория в базе не найдена',
            items: [
                {
                    id:0,
                    category: 0,
                    code:'',
                    name: 'Материал в базе не найден',
                    params:{
                        color: '#ffffff'
                    },
                    add_params:{

                    }
                }
            ]
        }
    },
    get_item: function (id) {

        if(this.items[id]){
            let copy = copy_object(this.items[id]);
            if(!copy.add_params) copy.add_params = {};
            return copy;
        } else {
            return {
                id:0,
                category: 0,
                code:'',
                name: 'Материал в базе не найден',
                params:{
                    color: '#ff0000',
                    transparent: true,
                    opacity:0.3
                },
                add_params:{

                }
            }
        }


    },
    get_category: function (id) {

        if (this.categories[id]) {
            return copy_object(this.categories[id]);
        } else {
            return {
                id:0,
                parent: 0,
                code:'',
                name: 'Категория в базе не найдена',
                items:[],
                categories:[]
            }
        }
    },
    get_data: function(item_id){
        let obj = {
            mat:'',
            cat:'',
            par_cat:'',
            str: ''
        }
        obj.mat = this.get_item(item_id);
        obj.cat = this.get_category(obj.mat.category);
        if (obj.cat.parent != 0) obj.par_cat = this.get_category(obj.cat.parent);

        obj.str = '';
        if (obj.cat.parent != 0) obj.str += obj.par_cat.name
        obj.str += ' ' + obj.cat.name;
        obj.str += ' ' + obj.mat.name;
        obj.str += ' ' + obj.mat.code;
        return obj;
    },
    reserved:{}
}

let cokol_top_height = 50;

let cokol_lib = {
    tree: '',
    items: {
        0: {
            "id": 0,
            "category": 0,
            "name": "",
            "model": "none",
            "model_radius": "none"
        }
    },
    categories: {
        0:{
            id:0,
            parent: 0,
            code:'',
            name: 'Категория в базе не найдена',
            items: [
                {
                    "id": 0,
                    "category": 0,
                    "name": "",
                    "model": "none",
                    "model_radius": "none"
                }
            ]
        }
    },
    get_item: function (id) {
        if(this.items[id]) return copy_object(this.items[id]);
        return copy_object(this.items[0])
    },
    get_category: function (id) {

        if (this.categories[id])  return copy_object(this.categories[id]);
        return copy_object(this.items[0])
    },
}

let cornice_lib = {
    tree: '',
    items: {
        0: {
            "id": 0,
            "category": 0,
            "name": "",
            "model": "/common_assets/models/cornice.fbx",
            "corner_model": "/common_assets/models/cornice_corner.fbx",
            "corner_model_45": "/common_assets/models/cornice_corner_45.fbx",
            "radius_model": "/common_assets/models/cornice_radius.fbx"
        }
    },
    categories: {
        0:{
            id:0,
            parent: 0,
            code:'',
            name: 'Категория в базе не найдена',
            items: [
                {
                    "id": 0,
                    "category": 0,
                    "name": "",
                    "model": "/common_assets/models/cornice.fbx",
                    "corner_model": "/common_assets/models/cornice_corner.fbx",
                    "corner_model_45": "/common_assets/models/cornice_corner_45.fbx",
                    "radius_model": "/common_assets/models/cornice_radius.fbx"
                }
            ]
        }
    },
    get_item: function (id) {
        if(this.items[id]) return copy_object(this.items[id]);
        return copy_object(this.items[0])
    },
    get_category: function (id) {

        if (this.categories[id])  return copy_object(this.categories[id]);
        return copy_object(this.items[0])
    },
}
var w_mat = new THREE.MeshPhongMaterial({
    color: 0xfffffff,
    // specular: 0xffffff,
    side: THREE.DoubleSide
});

texture_loader = new THREE.TextureLoader();
shadow_quality = {
    no: 2,
    low: 256,
    medium: 1024,
    high: 2048
};
global_options = {
    mode:'design'
};

let conf_sel_mat = new THREE.MeshBasicMaterial( {
    color: '#4c8613'
} );

white_mat = new THREE.MeshPhongMaterial({
    color: 0xfffffff,
    side: THREE.DoubleSide
});

aluminium_material = new THREE.MeshPhongMaterial({
    color: 0xbdbebf
});

gray_mat = new THREE.MeshPhongMaterial({
    color: 0xdadada,
    side: THREE.DoubleSide
});

leg_mat = new THREE.MeshBasicMaterial({color: 0x000000});

box3 = new THREE.Box3();

constructor_settings = {
    "price_enabled": "1",
    "shop_mode": "0",
    "default_kitchen_model": "",
    "show_kitchen_parameters": "1",
    "show_furniture": null,
    "multiple_facades_mode": 0,
    "cornice_available": "1",
    "custom_form": "0",
    "custom_order_url": "",
    "custom_sizes_available": "1",
    "tab_show_furniture": "1",
    "price_modificator": 1,
    "facade_style_change_availabale": 1,
    "show_specs": 0,
    "accessories_shop_enabled": "1",
    "facades_system_available": "1",
    "decorations_enabled": "1",
    "frontend_configurator_available": 1,
    "default_language": "ru"
};

facades_sets = [
    {
        "id": 1,
        "name": "Модерн",
        "category": 2,
        "icon": "models/facades/1/MDF_modern_gladkiy.jpg",
        "materials": [1, 11],
        "facades": {},
        "full": [{"min_width": "0", "min_height": "0", "model": "/common_assets/models/facades/mdf_full.fbx"}],
        "window": [{"min_width": "0", "min_height": "0", "model": "/common_assets/models/facades/mdf_frame.fbx"}],
        "frame": [
                {
                    "model": "/common_assets/models/facades/3/ark_full_min_0.fbx",
                    "min_width": 0,
                    "min_height": 0
                },
                {
                    "model": "/common_assets/models/facades/3/rect_frame_w396_h356.fbx",
                    "min_width": 296,
                    "min_height": 356
                },
                {
                    "model": "/common_assets/models/facades/3/rect_frame_w296_h716.fbx",
                    "min_width": 296,
                    "min_height": 716
                },
                {
                    "model": "/common_assets/models/facades/3/rect_frame_w296_h956.fbx",
                    "min_width": 296,
                    "min_height": 956
                }
        ],
        "radius": [{"min_width": "0", "min_height": "0", "model": "/common_assets/models/facades/mdf_full_radius.fbx"}],
        "radius_window": [{
            "min_width": "0",
            "min_height": "0",
            "model": "/common_assets/models/facades/mdf_window_radius.fbx"
        }],
        "radius_frame": null,
        "top_category": 1
    }
];

var models_catalog = {
    categories:[],
    items:[
        {
            id: 1000000,
            model: '/common_assets/models/bottle/bottle.fbx',
            material: {
                type: 'Phong',
                params: {
                    color: '#dadada'
                }
            },
            variants: [
                {
                    width: 105,
                    height: 515,
                    depth: 450
                }
            ],
            draggable: false,
        },
        {
            id: 2000000,
            model: '/common_assets/models/tech/built_in_cookers/1/model.fbx',
            material: {
                type: 'Phong',
                params: {
                    color: '#ffffff',
                    map: '/common_assets/models/tech/built_in_cookers/1/map.png',
                }
            },
            variants: [
                {
                    width: 596,
                    height: 596,
                    depth: 527
                }
            ],
            draggable: false,
        },
        {
            id: 2000001,
            model: '/common_assets/models/tech/built_in_cookers/1/model.fbx',
            material: {
                type: 'Phong',
                params: {
                    color: '#ffffff',
                    map: '/common_assets/models/tech/built_in_cookers/1/map_2.png',
                }
            },
            variants: [
                {
                    width: 596,
                    height: 596,
                    depth: 527
                }
            ],
            draggable: false,
        },
        {
            id: 2000002,
            model: '/common_assets/models/tech/built_in_cookers/2/model_2.fbx',
            material: {
                type: 'Phong',
                params: {
                    color: '#ffffff',
                    map: '/common_assets/models/tech/built_in_cookers/2/map.jpg',
                }
            },
            variants: [
                {
                    width: 596,
                    height: 596,
                    depth: 527
                }
            ],
            draggable: false,
        },
        {
            id: 2000003,
            model: '/common_assets/models/tech/built_in_cookers/2/OvenMic.fbx',
            material: {
                type: 'Phong',
                params: {
                    color: '#ffffff',
                    map: '/common_assets/models/tech/built_in_cookers/2/combo_map.jpg',
                }
            },
            variants: [
                {
                    width: 596,
                    height: 1000,
                    depth: 527
                }
            ],
            draggable: false,
        },
        {
            id: 2000004,
            model: '/common_assets/models/tech/built_in_cookers/2/OvenMic900.fbx',
            material: {
                type: 'Phong',
                params: {
                    color: '#ffffff',
                    map: '/common_assets/models/tech/built_in_cookers/2/combo_map.jpg',
                }
            },
            variants: [
                {
                    width: 596,
                    height: 900,
                    depth: 527
                }
            ],
            draggable: false,
        },
        {
            id: 2000005,
            model: '/common_assets/models/tech/built_in_cookers/2/OvenDouble.fbx',
            material: {
                type: 'Phong',
                params: {
                    color: '#ffffff',
                    map: '/common_assets/models/tech/built_in_cookers/2/map.jpg',
                }
            },
            variants: [
                {
                    width: 596,
                    height: 1200,
                    depth: 527
                }
            ],
            draggable: false,
        },
        {
            id: 3000000,
            model: '/common_assets/models/tech/cooking_panels/1/model.fbx',
            material: {
                type: 'Standart',
                params: {
                    color: '#ffffff',
                    map: '/common_assets/models/tech/cooking_panels/1/map.jpg',
                    metalness: 0.1,
                    roughness: 0.1
                }
            },
            variants: [
                {
                    width: 583,
                    height: 8,
                    depth: 512
                }
            ],
            draggable: false,
        },
        {
            id: 4000000,
            model: '/common_assets/models/tech/sinks/1/sink.fbx',
            material: {
                type: 'Standart',
                params: {
                    color: '#dadada',
                    // map: '/common_assets/models/tech/cooking_panels/1/map.jpg',
                    metalness: 0.1,
                    roughness: 0.1
                }
            },
            variants: [
                {
                    width: 496,
                    height: 343,
                    depth: 480
                }
            ],
            draggable: false,
        },
        {
            id: 5000000,
            model: '/common_assets/models/custom/ya2_inn.fbx',
            material: {
                type: 'Phong',
                params: {
                    color: '#87919c'
                }
            },
            variants: [
                {
                    width: 105,
                    height: 515,
                    depth: 450
                }
            ],
            draggable: false,
        },
        {
            id: 6000000,
            model: '/common_assets/models/custom/ya1.fbx',
            material: {
                type: 'Phong',
                params: {
                    color: '#87919c'
                }
            },
            variants: [
                {
                    width: 105,
                    height: 515,
                    depth: 450
                }
            ],
            draggable: false,
        },
    ]
};
var edges_white_mat = new THREE.MeshPhongMaterial({
    color: 0xfffffff,
    side: THREE.DoubleSide,
    transparent: true,
    opacity: 0.7
});

var fbx_manager = new THREE.LoadingManager();
var fbx_loader = new THREE.FBXLoader(fbx_manager);

function init_three_test(element_id) {

    convert_lib(materials_lib, copy_object(materials_catalog))

    if(!project_settings.facades_data){
        project_settings.facades_data = {
            top: {},
            bottom:{}
        };

        project_settings.facades_data.top = objectFindByKey(facades_sets, 'id', project_settings.models['top']);
        project_settings.facades_data.bottom = objectFindByKey(facades_sets, 'id', project_settings.models['bottom']);

        if(!project_settings.facades_data.top) project_settings.facades_data.top = facades_sets[0];
        if(!project_settings.facades_data.bottom) project_settings.facades_data.bottom = facades_sets[0];

        console.log()

        if(project_settings.fixed_materials){

            if(!project_settings.facades_data.top.additional_materials) project_settings.facades_data.top.additional_materials = {};

            for (let i = 0; i < project_settings.fixed_materials.length; i++){
                project_settings.facades_data.top.additional_materials['mat'+(i+1)] = {
                    fixed: true,
                    name: '',
                    selected: project_settings.fixed_materials[i]
                }
            }
        }
    }

    env_urls = [
        "/common_assets/tests/prod/test12.jpg",
        "/common_assets/tests/prod/test12.jpg",
        "/common_assets/tests/prod/test12.jpg",
        "/common_assets/tests/prod/test12.jpg",
        "/common_assets/tests/prod/test12.jpg",
        "/common_assets/tests/prod/test12.jpg"
    ];





    textureCube2 = new THREE.CubeTextureLoader().load( env_urls );
    textureCube2.format = THREE.RGBFormat;
    textureCube2.mapping = THREE.CubeReflectionMapping;

    state_panel = document.getElementById('state_panel');


    var viewport = document.getElementById(element_id);

    scene = new THREE.Scene();
    scene.fog = new THREE.Fog(0xFFFFFF, 500, 10000);
    camera = new THREE.PerspectiveCamera( 55, viewport.clientWidth/viewport.clientHeight, 0.1, 1000 );

    renderer = new THREE.WebGLRenderer({
        antialias: true,
        preserveDrawingBuffer: true
    });
    renderer.setClearColor(scene.fog.color);
    renderer.setSize( viewport.clientWidth, viewport.clientHeight );

    viewport.appendChild( renderer.domElement );

    fbx_manager = new THREE.LoadingManager();
    loader = new THREE.FBXLoader(fbx_manager);




    amb_light = new THREE.AmbientLight(0xffffff, 0.03);
    scene.add(amb_light);






    room = new Room_new({
        width: 200 * units,
        height: 200 * units,
        depth: 200 * units
    });



    scene.add(room);






    let errors = [];





    controls = new THREE.OrbitControls(camera, renderer.domElement);

    set_configurator_mode();


    var animate = function () {
        requestAnimationFrame( animate );

        // cube.rotation.x += 0.01;
        // cube.rotation.y += 0.01;

        renderer.render( scene, camera );
    };

    animate();


}


function convert_lib(lib, input, height) {
    let data = copy_object(input);

    let cat_h = Object.create(null)

    data.categories.forEach(function (obj) {
        obj.categories = [];
        obj.items = [];
        cat_h[obj.id] = obj;
    })

    // data.categories.forEach( aData => cat_h[aData.id] = { ...aData, categories : [] } )
    lib.categories = cat_h;
    lib.items = get_hash(data.items);

    for (let i = 0; i < data.items.length; i++){
        if(height) data.items[i].height = data.items[i].params.variants[0].height;
        if (data.items[i].category in cat_h) {
            if (!cat_h[data.items[i].category].items) cat_h[data.items[i].category].items = [];
            cat_h[data.items[i].category].items.push(data.items[i])
        }
    }

    let dataTree = []
    for (let i = 0; i < data.categories.length; i++){
        if( data.categories[i].parent ) cat_h[data.categories[i].parent].categories.push(cat_h[data.categories[i].id])
        else dataTree.push(cat_h[data.categories[i].id])
    }

    lib.tree = dataTree
}


function Wall(params_obj) {

    // obj = {
    //     width: float,
    //     height: float,
    //     rX: float,
    //     rY: float,
    //     rZ: float,
    //     pX: float,
    //     pY: float,
    //     pZ: float,
    //     name: string,
    // }

    var width = params_obj.width ? params_obj.width : 0;
    var height = params_obj.height ? params_obj.height : 0;

    var rX = params_obj.rX ? params_obj.rX : 0;
    var rY = params_obj.rY ? params_obj.rY : 0;
    var rZ = params_obj.rZ ? params_obj.rZ : 0;

    var pX = params_obj.pX ? params_obj.pX : 0;
    var pY = params_obj.pY ? params_obj.pY : 0;
    var pZ = params_obj.pZ ? params_obj.pZ : 0;

    var name = params_obj.name ? params_obj.name : 'wall';


    var material;
    if (params_obj.material) {
        material = params_obj.material;
    } else {
        material = new THREE.MeshPhongMaterial({
            color: 0xffffff,
            specular: 0x000000,
            // side: THREE.DoubleSide
        });
    }

    var geometry = new THREE.PlaneGeometry(width, height);


    var mesh = new THREE.Mesh(geometry, material);
    mesh.rotation.set(rX, rY, rZ);
    mesh.position.set(pX, pY, pZ);
    mesh.receiveShadow = true;
    mesh.castShadow = false;
    mesh.name = name;
    mesh.is_wall = true;

    return mesh;
}

window.addEventListener('resize', onWindowResize, false);

function onWindowResize() {
    var viewport = document.getElementById('three_viewport');
    camera.aspect = viewport.clientWidth / viewport.clientHeight;
    camera.updateProjectionMatrix();
    renderer.setSize( viewport.clientWidth, viewport.clientHeight );
}