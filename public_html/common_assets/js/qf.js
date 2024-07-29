let int = setInterval(function () {
    if(typeof renderer !== "undefined"){
        clearInterval(int)
        setTimeout(function () {
            let size = renderer.getSize();
            renderer.setSize(size.x+1, size.y)
            renderer.setSize(size.x, size.y)
        },500)


        setTimeout(function () {
            if(window.location.href.split('?')[0].toLowerCase() === 'https://broskokitchenplanner.com/clients/dev/'){
                $('#load_button').off().click(function () {
                    show_demo_modal();
                })

                $('#save_button').off().click(function () {
                    show_demo_modal();
                })

                $('#obj_export_button').off().click(function () {
                    show_demo_modal();
                })

                $('#gltf_export_button').off().click(function () {
                    show_demo_modal();
                })

                $('#screen_save').off().click(function () {
                    show_demo_modal();
                })
            }
        },2500)



    }
},100)


