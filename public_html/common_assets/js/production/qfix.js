
if(location.href.indexOf('16458585') > -1){
    BPObject.prototype.del_spl = function () {
        for (let i = this.children.length; i--;){
            this.remove(this.children[i]);
        }

        for (let i = room.active_objects.length; i--;){
            if (this.uuid === room.active_objects[i].uuid) {
                room.remove(this);
                room.active_objects.splice(i, 1)
            }
        }
        for (let i = d_objects.length; i--;){
            if(this.uuid == d_objects[i].uuid) d_objects.splice(i, 1)
        }

        sc.get_price();

    }

    function update_price_info_panel(){
        update_materials_panel();
    }


}


