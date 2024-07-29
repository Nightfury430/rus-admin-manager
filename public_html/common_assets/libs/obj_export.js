/**
 * @author mrdoob / http://mrdoob.com/
 */

function get_parent(mesh){

}



THREE.OBJExporter = function () {};

THREE.OBJExporter.prototype = {

    constructor: THREE.OBJExporter,

    parse: function ( object ) {

        var output = '';

        var indexVertex = 0;
        var indexVertexUvs = 0;
        var indexNormals = 0;

        var vertex = new THREE.Vector3();
        var normal = new THREE.Vector3();
        var uv = new THREE.Vector2();

        var i, j, k, l, m, face = [];

        var parseMesh = function ( mesh ) {

            if(mesh.parent.type === 'Sizes_obj') return;

            if(mesh.visible == false) return;




            if(mesh.size_box ) return;



            var nbVertex = 0;
            var nbNormals = 0;
            var nbVertexUvs = 0;

            var geometry = mesh.geometry;

            var normalMatrixWorld = new THREE.Matrix3();

            if ( geometry instanceof THREE.Geometry ) {

                geometry = new THREE.BufferGeometry().setFromObject( mesh );


            }

            if ( geometry instanceof THREE.BufferGeometry ) {

                // shortcuts
                var vertices = geometry.getAttribute( 'position' );
                var normals = geometry.getAttribute( 'normal' );
                var uvs = geometry.getAttribute( 'uv' );
                var indices = geometry.getIndex();

                var name = mesh.parent.name;


                if(mesh.parent){
                    if(mesh.parent.type === 'Shaft'){
                        if(mesh.parent.params.width > mesh.parent.params.depth){
                            name = parseInt(mesh.parent.params.width) + "," + parseInt(mesh.parent.params.height) + ";" + room.params.wall_material + ";"  + 'Shaft_wall' + "_" + mesh.uuid;
                        } else {
                            name = parseInt(mesh.parent.params.depth) + "," + parseInt(mesh.parent.params.height) + ";" + room.params.wall_material + ";" + 'Shaft_wall' + "_" + mesh.uuid;
                        }

                    }
                }


                if (name === 'Cokol'){
                    name = parseInt(mesh.params.width*10) + "," + parseInt(mesh.params.height*10) + ";" + mesh.params.material + ';' + mesh.parent.name + '_' + mesh.uuid;
                }

                if(mesh.parent){
                    if(mesh.parent.type === 'Cornice'){
                        if (mesh.parent.visible === false){
                            return
                        }

                        let b = box3.setFromObject(mesh).getSize();


                        if(mesh.parent.rotation === 0 || mesh.parent.rotation === Math.PI){
                            name = parseInt(b.x * units) + "," + parseInt(b.y * units) +";" + project_settings.selected_materials.top.facades +";Cornice_top";
                        } else {
                            name = parseInt(b.z * units) + "," + parseInt(b.y * units) +";"+ project_settings.selected_materials.top.facades + ";Cornice_top";
                        }

                    }

                    // if(mesh.parent.type === 'Facade'){
                    //     if (mesh.parent.visible === false){
                    //         return
                    //     }
                    //     let b = box3.setFromObject(mesh).getSize();
                    //
                    //     name = parseInt(mesh.parent.parent.params.width) + "," + parseInt(mesh.parent.parent.params.height) + ";" + mesh.parent.parent.params.material +";"+"Facade_" + mesh.parent.parent.params.group;
                    //
                    // }

                }


                if(name === 'Shelve'){
                    name = parseInt(mesh.parent.parent.params.width) + "," + parseInt(mesh.parent.parent.params.height) + ";" + mesh.parent.parent.params.material +";"+"Shelve_" + mesh.uuid;
                }



                if(name === "Tabletop"){
                    name = parseInt(mesh.parent.params.width) + "," + parseInt(mesh.parent.params.depth) + ";" + mesh.parent.params.material +  ";" + mesh.parent.name;
                }

                if(name === "wall_panel"){
                    name = parseInt(mesh.parent.params.width*10) + "," + parseInt(mesh.parent.params.height*10) + ";" + project_settings.selected_materials.wall_panel + ";" + mesh.parent.name;
                }

                if(name === "Facade"){

                    if(project_settings.fixed_materials === undefined || project_settings.fixed_materials === null || project_settings.fixed_materials === ''){
                        project_settings.fixed_materials = [];
                    }
                    let mats = project_settings.fixed_materials;

                    let flag = 0;

                    for (let i = 0; i < mats.length; i++) {
                        let str = (i + 1);


                        if (mesh.name.indexOf('mat' + str) > -1) {
                            name = parseInt(mesh.parent.parent.params.width) + "," + parseInt(mesh.parent.parent.params.height) + ";" + mats[i] +";"+"Facade_" + mesh.parent.parent.params.group + "_" + mesh.uuid;
                            flag = 1;
                        }
                    }

                    if(flag == 0){
                        if( mesh.name.indexOf('pat') > -1){
                            let b = box3.setFromObject(mesh).getSize();

                            if(mesh.parent.parent.parent.parent.parent.rotation.y === 0 || mesh.parent.parent.parent.parent.parent.rotation.y === Math.PI){
                                name = parseInt(b.x * units) + "," + parseInt(b.y * units) +";" + project_settings.selected_materials.pat + ";"+"Pat_" + mesh.parent.parent.params.group + "_" + mesh.uuid;
                            } else {
                                name = parseInt(b.z * units) + "," + parseInt(b.y * units) +";" + project_settings.selected_materials.pat + ";"+"Pat_" + mesh.parent.parent.params.group + "_" + mesh.uuid;
                            }


                        } else if(mesh.name.indexOf('botfacmat') > -1){



                            let b = box3.setFromObject(mesh).getSize();

                            if(mesh.parent.parent.parent.parent.parent.rotation.y === 0 || mesh.parent.parent.parent.parent.parent.rotation.y === Math.PI){
                                name = parseInt(b.x * units) + "," + parseInt(b.y * units) +";" + project_settings.selected_materials.bottom.facades + ";"+"Facade_bottom" + "_" + mesh.uuid;
                            } else {
                                name = parseInt(b.z * units) + "," + parseInt(b.y * units) +";" + project_settings.selected_materials.bottom.facades + ";"+"Facade_bottom" + "_" + mesh.uuid;
                            }


                        } else {
                            name = parseInt(mesh.parent.parent.params.width) + "," + parseInt(mesh.parent.parent.params.height) + ";" + mesh.parent.parent.params.material +";"+"Facade_" + mesh.parent.parent.params.group + "_" + mesh.uuid;

                        }
                    }




                }

                if(mesh.name.indexOf('glass') !== -1 ){
                    name = 'Glass';

                    if(mesh.parent.parent){
                       if( mesh.parent.parent.type == 'Facade'){
                           console.log(mesh.parent.parent)

                           let fac = mesh.parent.parent;
                           if(fac.params.glass_material !== glass_mat){
                               let glass_material = get_material_by_id(fac.params.glass_material, glass_materials_catalog);
                               let b = box3.setFromObject(mesh).getSize();


                               if(fac.parent.parent.parent.rotation.y === 0 || fac.parent.parent.parent.rotation.y === Math.PI) {

                                   name = parseInt(b.x * units) + "," + parseInt(b.y * units) + ";" + glass_material.id + ";" + "Glass_" + mesh.uuid;
                               } else {
                                   name = parseInt(b.z * units) + "," + parseInt(b.y * units) + ";" + glass_material.id + ";" + "Glass_" + mesh.uuid;

                               }
                           }

                       }
                    }

                }

                if(mesh.name.indexOf('mirror') !== -1){
                    name = 'Mirror';
                }

                if(mesh.parent === room.walls){

                    if(mesh.name === "floor") name = parseInt(room.params.width) + "," + parseInt(room.params.depth) + ";" + project_settings.selected_materials.floor + ";" + mesh.name;
                    if(mesh.name === 'front_wall' || mesh.name === 'back_wall') name = parseInt(room.params.width) + "," + parseInt(room.params.height) + ";" + room.params.wall_material + ";" + mesh.name;
                    if(mesh.name === 'left_wall' || mesh.name === 'right_wall') name = parseInt(room.params.depth) + "," + parseInt(room.params.height) + ";" + room.params.wall_material + ";" + mesh.name;

                }

                if(mesh.parent.name === 'Cabinet'){

                    if(mesh.params.width){
                        name = parseInt(mesh.params.width) + "," + parseInt(mesh.params.height) + ";" + mesh.parent.parent.params.cabinet.material + ";" + mesh.parent.name + '_' + mesh.parent.parent.params.cabinet_group + "_" + mesh.uuid;
                    } else {
                        name = "100,100;" + mesh.parent.parent.params.cabinet.material + ";" + mesh.parent.name + '_' + mesh.parent.parent.params.cabinet_group + "_" + mesh.uuid;
                    }
                }

                if(mesh.parent.name === "Shelves"){

                    if(mesh.params.width){
                        name = parseInt(mesh.params.width) + "," + parseInt(mesh.params.height) + ";" + mesh.parent.parent.params.cabinet.material + ";" + mesh.parent.name + '_' + mesh.parent.parent.params.cabinet_group + "_" + mesh.uuid;
                    } else {
                        name = "100,100;" + mesh.parent.parent.params.cabinet.material + ";" + mesh.parent.name + '_' + mesh.parent.parent.params.cabinet_group + "_" + mesh.uuid;
                    }

                    // name = mesh.parent.parent.params.cabinet.material + ";" + mesh.parent.name + '_' + mesh.parent.parent.params.cabinet_group + "_" + mesh.uuid;
                }

                if(mesh.parent.parent){


                    if(mesh.parent.parent.type === 'Model'){




                        var arr = mesh.parent.parent.params.model.split('/');


                        if(arr[1] !== "common_assets"){
                            name = "Model_" + arr[1] + "_" + arr[2] + "_" + mesh.uuid;
                        } else {
                            name = "Model_" + arr[3] + "_" + mesh.parent.parent.params.id + "_" + mesh.uuid;
                        }

                        if(mesh.parent.parent.params.model.indexOf('bottle') > -1){
                            name = 'Bottle' + mesh.uuid;
                        }

                        if(mesh.parent.parent.params.model.indexOf('built_in_cookers') > -1){
                            name = 'Builtcook_' + mesh.uuid;
                        }


                        if(mesh.parent.parent.params.model.indexOf('cooking_panels') > -1){
                            name = 'Cookpanel_' + mesh.uuid;
                        }

                        if(mesh.parent.parent.params.model.indexOf('sinks') > -1){
                            name = 'Sink_' + mesh.uuid;
                        }



                        if(mesh.parent.parent.params.model.indexOf('window') > -1){
                            if (mesh.name.indexOf('window') > -1){
                                name = 'wingl_' + mesh.uuid;
                            } else {
                                name = 'Window_' + mesh.uuid;
                            }

                        }

                        if(mesh.parent.parent.params.model.indexOf('door') > -1){
                            name = 'Door_' + mesh.uuid;
                        }

                        if(mesh.name.indexOf('facmat') > -1){

                            gh = mesh;

                            name = mesh.parent.parent.params.width + ','+ mesh.parent.parent.params.height +';'+ project_settings.selected_materials.top.facades +';Facade_' + mesh.parent.parent.cabinet_type;
                        }

                        if(mesh.name.indexOf('mirror')>-1){
                            name = 'Mirror_' +  mesh.uuid
                        }

                    }

                    if(mesh.parent.parent.type == 'Bardesk'){
                        name = 'Sink_' + mesh.uuid;
                    }

                    if(mesh.parent.parent.type == 'Washer' || mesh.parent.parent.type == 'Simple_model'){
                        name = 'Sink_' + mesh.uuid;


                        if(mesh.name.indexOf('cut') > -1 || mesh.name.indexOf('smes') > -1) return;

                    }
                }



                // name of the mesh object
                output += 'o ' + name + '_' + mesh.parent.uuid + '\n';

                // name of the mesh material
                if ( mesh.material && mesh.material.name ) {

                    output += 'usemtl ' + mesh.material.name + '\n';

                }

                // vertices

                if ( vertices !== undefined ) {

                    for ( i = 0, l = vertices.count; i < l; i ++, nbVertex ++ ) {

                        vertex.x = vertices.getX( i );
                        vertex.y = vertices.getY( i );
                        vertex.z = vertices.getZ( i );

                        // transfrom the vertex to world space
                        vertex.applyMatrix4( mesh.matrixWorld );

                        // transform the vertex to export format
                        output += 'v ' + vertex.x + ' ' + vertex.y + ' ' + vertex.z + '\n';

                    }

                }

                // uvs

                if ( uvs !== undefined ) {

                    for ( i = 0, l = uvs.count; i < l; i ++, nbVertexUvs ++ ) {

                        uv.x = uvs.getX( i );
                        uv.y = uvs.getY( i );

                        // transform the uv to export format
                        output += 'vt ' + uv.x + ' ' + uv.y + '\n';

                    }

                }

                // normals

                if ( normals !== undefined ) {

                    normalMatrixWorld.getNormalMatrix( mesh.matrixWorld );

                    for ( i = 0, l = normals.count; i < l; i ++, nbNormals ++ ) {

                        normal.x = normals.getX( i );
                        normal.y = normals.getY( i );
                        normal.z = normals.getZ( i );

                        // transfrom the normal to world space
                        normal.applyMatrix3( normalMatrixWorld );

                        // transform the normal to export format
                        output += 'vn ' + normal.x + ' ' + normal.y + ' ' + normal.z + '\n';

                    }

                }

                // faces

                if ( indices !== null ) {

                    for ( i = 0, l = indices.count; i < l; i += 3 ) {

                        for ( m = 0; m < 3; m ++ ) {

                            j = indices.getX( i + m ) + 1;

                            face[ m ] = ( indexVertex + j ) + ( normals || uvs ? '/' + ( uvs ? ( indexVertexUvs + j ) : '' ) + ( normals ? '/' + ( indexNormals + j ) : '' ) : '' );

                        }

                        // transform the face to export format
                        output += 'f ' + face.join( ' ' ) + "\n";

                    }

                } else {

                    for ( i = 0, l = vertices.count; i < l; i += 3 ) {

                        for ( m = 0; m < 3; m ++ ) {

                            j = i + m + 1;

                            face[ m ] = ( indexVertex + j ) + ( normals || uvs ? '/' + ( uvs ? ( indexVertexUvs + j ) : '' ) + ( normals ? '/' + ( indexNormals + j ) : '' ) : '' );

                        }

                        // transform the face to export format
                        output += 'f ' + face.join( ' ' ) + "\n";

                    }

                }

            } else {

                console.warn( 'THREE.OBJExporter.parseMesh(): geometry type unsupported', geometry );

            }

            // update index
            indexVertex += nbVertex;
            indexVertexUvs += nbVertexUvs;
            indexNormals += nbNormals;

        };

        var parseLine = function ( line ) {

            var nbVertex = 0;

            var geometry = line.geometry;
            var type = line.type;

            if ( geometry instanceof THREE.Geometry ) {

                geometry = new THREE.BufferGeometry().setFromObject( line );

            }

            if ( geometry instanceof THREE.BufferGeometry ) {

                // shortcuts
                var vertices = geometry.getAttribute( 'position' );

                // name of the line object
                output += 'o ' + line.uuid + '\n';

                if ( vertices !== undefined ) {

                    for ( i = 0, l = vertices.count; i < l; i ++, nbVertex ++ ) {

                        vertex.x = vertices.getX( i );
                        vertex.y = vertices.getY( i );
                        vertex.z = vertices.getZ( i );

                        // transfrom the vertex to world space
                        vertex.applyMatrix4( line.matrixWorld );

                        // transform the vertex to export format
                        output += 'v ' + vertex.x + ' ' + vertex.y + ' ' + vertex.z + '\n';

                    }

                }

                if ( type === 'Line' ) {

                    output += 'l ';

                    for ( j = 1, l = vertices.count; j <= l; j ++ ) {

                        output += ( indexVertex + j ) + ' ';

                    }

                    output += '\n';

                }

                if ( type === 'LineSegments' ) {

                    for ( j = 1, k = j + 1, l = vertices.count; j < l; j += 2, k = j + 1 ) {

                        output += 'l ' + ( indexVertex + j ) + ' ' + ( indexVertex + k ) + '\n';

                    }

                }

            } else {

                console.warn( 'THREE.OBJExporter.parseLine(): geometry type unsupported', geometry );

            }

            // update index
            indexVertex += nbVertex;

        };

        object.traverse( function ( child ) {

            if ( child instanceof THREE.Mesh ) {

                parseMesh( child );

            }

            if ( child instanceof THREE.Line ) {

                parseLine( child );

            }

        } );

        return output;

    }

};