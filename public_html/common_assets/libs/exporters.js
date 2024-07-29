/**
 * @author mrdoob / http://mrdoob.com/
 */

THREE.ColladaExporter = function () {};

THREE.ColladaExporter.prototype = {

    constructor: THREE.ColladaExporter,

    parse: function ( object, onDone, options ) {

        options = options || {};

        options = Object.assign( {
            version: '1.4.1',
            author: null,
            textureDirectory: '',
        }, options );

        if ( options.textureDirectory !== '' ) {

            options.textureDirectory = `${ options.textureDirectory }/`
                .replace( /\\/g, '/' )
                .replace( /\/+/g, '/' );

        }

        var version = options.version;
        if ( version !== '1.4.1' && version !== '1.5.0' ) {

            console.warn( `ColladaExporter : Version ${ version } not supported for export. Only 1.4.1 and 1.5.0.` );
            return null;

        }

        // Convert the urdf xml into a well-formatted, indented format
        function format( urdf ) {

            var IS_END_TAG = /^<\//;
            var IS_SELF_CLOSING = /(\?>$)|(\/>$)/;
            var HAS_TEXT = /<[^>]+>[^<]*<\/[^<]+>/;

            var pad = ( ch, num ) => ( num > 0 ? ch + pad( ch, num - 1 ) : '' );

            var tagnum = 0;
            return urdf
                .match( /(<[^>]+>[^<]+<\/[^<]+>)|(<[^>]+>)/g )
                .map( tag => {

                    if ( ! HAS_TEXT.test( tag ) && ! IS_SELF_CLOSING.test( tag ) && IS_END_TAG.test( tag ) ) {

                        tagnum --;

                    }

                    var res = `${ pad( '  ', tagnum ) }${ tag }`;

                    if ( ! HAS_TEXT.test( tag ) && ! IS_SELF_CLOSING.test( tag ) && ! IS_END_TAG.test( tag ) ) {

                        tagnum ++;

                    }

                    return res;

                } )
                .join( '\n' );

        }

        // Convert an image into a png format for saving
        function base64ToBuffer( str ) {

            var b = atob( str );
            var buf = new Uint8Array( b.length );

            for ( var i = 0, l = buf.length; i < l; i ++ ) {

                buf[ i ] = b.charCodeAt( i );

            }

            return buf;

        }

        var canvas, ctx;
        function imageToData( image, ext ) {

            canvas = canvas || document.createElement( 'canvas' );
            ctx = ctx || canvas.getContext( '2d' );

            canvas.width = image.naturalWidth;
            canvas.height = image.naturalHeight;

            ctx.drawImage( image, 0, 0 );

            // Get the base64 encoded data
            var base64data = canvas
                .toDataURL( `image/${ ext }`, 1 )
                .replace( /^data:image\/(png|jpg);base64,/, '' );

            // Convert to a uint8 array
            return base64ToBuffer( base64data );

        }

        // gets the attribute array. Generate a new array if the attribute is interleaved
        var getFuncs = [ 'getX', 'getY', 'getZ', 'getW' ];
        function attrBufferToArray( attr ) {

            if ( attr.isInterleavedBufferAttribute ) {

                // use the typed array constructor to save on memory
                var arr = new attr.array.constructor( attr.count * attr.itemSize );
                var size = attr.itemSize;
                for ( var i = 0, l = attr.count; i < l; i ++ ) {

                    for ( var j = 0; j < size; j ++ ) {

                        arr[ i * size + j ] = attr[ getFuncs[ j ] ]( i );

                    }

                }

                return arr;

            } else {

                return attr.array;

            }

        }

        // Returns an array of the same type starting at the `st` index,
        // and `ct` length
        function subArray( arr, st, ct ) {

            if ( Array.isArray( arr ) ) return arr.slice( st, st + ct );
            else return new arr.constructor( arr.buffer, st * arr.BYTES_PER_ELEMENT, ct );

        }

        // Returns the string for a geometry's attribute
        function getAttribute( attr, name, params, type ) {

            var array = attrBufferToArray( attr );
            var res =
                `<source id="${ name }">` +

                `<float_array id="${ name }-array" count="${ array.length }">` +
                array.join( ' ' ) +
                '</float_array>' +

                '<technique_common>' +
                `<accessor source="#${ name }-array" count="${ Math.floor( array.length / attr.itemSize ) }" stride="${ attr.itemSize }">` +

                params.map( n => `<param name="${ n }" type="${ type }" />` ).join( '' ) +

                '</accessor>' +
                '</technique_common>' +
                '</source>';

            return res;

        }

        // Returns the string for a node's transform information
        var transMat;
        function getTransform( o ) {

            // ensure the object's matrix is up to date
            // before saving the transform
            o.updateMatrix();

            transMat = transMat || new THREE.Matrix4();
            transMat.copy( o.matrix );
            transMat.transpose();
            return `<matrix>${ transMat.toArray().join( ' ' ) }</matrix>`;

        }

        // Process the given piece of geometry into the geometry library
        // Returns the mesh id
        function processGeometry( g ) {

            var info = geometryInfo.get( g );

            if ( ! info ) {

                // convert the geometry to bufferGeometry if it isn't already
                var bufferGeometry = g;
                if ( bufferGeometry instanceof THREE.Geometry ) {

                    bufferGeometry = ( new THREE.BufferGeometry() ).fromGeometry( bufferGeometry );

                }

                var meshid = `Mesh${ libraryGeometries.length + 1 }`;

                var indexCount =
                    bufferGeometry.index ?
                        bufferGeometry.index.count * bufferGeometry.index.itemSize :
                        bufferGeometry.attributes.position.count;

                var groups =
                    bufferGeometry.groups != null && bufferGeometry.groups.length !== 0 ?
                        bufferGeometry.groups :
                        [ { start: 0, count: indexCount, materialIndex: 0 } ];


                var gname = g.name ? ` name="${ g.name }"` : '';
                var gnode = `<geometry id="${ meshid }"${ gname }><mesh>`;

                // define the geometry node and the vertices for the geometry
                var posName = `${ meshid }-position`;
                var vertName = `${ meshid }-vertices`;
                gnode += getAttribute( bufferGeometry.attributes.position, posName, [ 'X', 'Y', 'Z' ], 'float' );
                gnode += `<vertices id="${ vertName }"><input semantic="POSITION" source="#${ posName }" /></vertices>`;

                // NOTE: We're not optimizing the attribute arrays here, so they're all the same length and
                // can therefore share the same triangle indices. However, MeshLab seems to have trouble opening
                // models with attributes that share an offset.
                // MeshLab Bug#424: https://sourceforge.net/p/meshlab/bugs/424/

                // serialize normals
                var triangleInputs = `<input semantic="VERTEX" source="#${ vertName }" offset="0" />`;
                if ( 'normal' in bufferGeometry.attributes ) {

                    var normName = `${ meshid }-normal`;
                    gnode += getAttribute( bufferGeometry.attributes.normal, normName, [ 'X', 'Y', 'Z' ], 'float' );
                    triangleInputs += `<input semantic="NORMAL" source="#${ normName }" offset="0" />`;

                }

                // serialize uvs
                if ( 'uv' in bufferGeometry.attributes ) {

                    var uvName = `${ meshid }-texcoord`;
                    gnode += getAttribute( bufferGeometry.attributes.uv, uvName, [ 'S', 'T' ], 'float' );
                    triangleInputs += `<input semantic="TEXCOORD" source="#${ uvName }" offset="0" set="0" />`;

                }

                // serialize colors
                if ( 'color' in bufferGeometry.attributes ) {

                    var colName = `${ meshid }-color`;
                    gnode += getAttribute( bufferGeometry.attributes.color, colName, [ 'X', 'Y', 'Z' ], 'uint8' );
                    triangleInputs += `<input semantic="COLOR" source="#${ colName }" offset="0" />`;

                }

                var indexArray = null;
                if ( bufferGeometry.index ) {

                    indexArray = attrBufferToArray( bufferGeometry.index );

                } else {

                    indexArray = new Array( indexCount );
                    for ( var i = 0, l = indexArray.length; i < l; i ++ ) indexArray[ i ] = i;

                }

                for ( var i = 0, l = groups.length; i < l; i ++ ) {

                    var group = groups[ i ];
                    var subarr = subArray( indexArray, group.start, group.count );
                    var polycount = subarr.length / 3;
                    gnode += `<triangles material="MESH_MATERIAL_${ group.materialIndex }" count="${ polycount }">`;
                    gnode += triangleInputs;

                    gnode += `<p>${ subarr.join( ' ' ) }</p>`;
                    gnode += '</triangles>';

                }

                gnode += `</mesh></geometry>`;

                libraryGeometries.push( gnode );

                info = { meshid: meshid, bufferGeometry: bufferGeometry };
                geometryInfo.set( g, info );

            }

            return info;

        }

        // Process the given texture into the image library
        // Returns the image library
        function processTexture( tex ) {

            var texid = imageMap.get( tex );
            if ( texid == null ) {

                texid = `image-${ libraryImages.length + 1 }`;

                var ext = 'png';
                var name = tex.name || texid;
                var imageNode = `<image id="${ texid }" name="${ name }">`;

                if ( version === '1.5.0' ) {

                    imageNode += `<init_from><ref>${ options.textureDirectory }${ name }.${ ext }</ref></init_from>`;

                } else {

                    // version image node 1.4.1
                    imageNode += `<init_from>${ options.textureDirectory }${ name }.${ ext }</init_from>`;

                }

                imageNode += '</image>';

                libraryImages.push( imageNode );
                imageMap.set( tex, texid );
                textures.push( {
                    directory: options.textureDirectory,
                    name,
                    ext,
                    data: imageToData( tex.image, ext ),
                    original: tex
                } );

            }

            return texid;

        }

        // Process the given material into the material and effect libraries
        // Returns the material id
        function processMaterial( m ) {

            var matid = materialMap.get( m );

            if ( matid == null ) {

                matid = `Mat${ libraryEffects.length + 1 }`;

                var type = 'phong';

                if ( m instanceof THREE.MeshLambertMaterial ) {

                    type = 'lambert';

                } else if ( m instanceof THREE.MeshBasicMaterial ) {

                    type = 'constant';

                    if ( m.map !== null ) {

                        // The Collada spec does not support diffuse texture maps with the
                        // constant shader type.
                        // mrdoob/three.js#15469
                        console.warn( 'ColladaExporter: Texture maps not supported with MeshBasicMaterial.' );

                    }

                }

                var emissive = m.emissive ? m.emissive : new THREE.Color( 0, 0, 0 );
                var diffuse = m.color ? m.color : new THREE.Color( 0, 0, 0 );
                var specular = m.specular ? m.specular : new THREE.Color( 1, 1, 1 );
                var shininess = m.shininess || 0;
                var reflectivity = m.reflectivity || 0;

                // Do not export and alpha map for the reasons mentioned in issue (#13792)
                // in three.js alpha maps are black and white, but collada expects the alpha
                // channel to specify the transparency
                var transparencyNode = '';
                if ( m.transparent === true ) {

                    transparencyNode +=
                        `<transparent>` +
                        (
                            m.map ?
                                `<texture texture="diffuse-sampler"></texture>` :
                                '<float>1</float>'
                        ) +
                        '</transparent>';

                    if ( m.opacity < 1 ) {

                        transparencyNode += `<transparency><float>${ m.opacity }</float></transparency>`;

                    }

                }

                var techniqueNode = `<technique sid="common"><${ type }>` +

                    '<emission>' +

                    (
                        m.emissiveMap ?
                            '<texture texture="emissive-sampler" texcoord="TEXCOORD" />' :
                            `<color sid="emission">${ emissive.r } ${ emissive.g } ${ emissive.b } 1</color>`
                    ) +

                    '</emission>' +

                    (
                        type !== 'constant' ?
                            '<diffuse>' +

                            (
                                m.map ?
                                    '<texture texture="diffuse-sampler" texcoord="TEXCOORD" />' :
                                    `<color sid="diffuse">${ diffuse.r } ${ diffuse.g } ${ diffuse.b } 1</color>`
                            ) +
                            '</diffuse>'
                            : ''
                    ) +

                    (
                        type === 'phong' ?
                            `<specular><color sid="specular">${ specular.r } ${ specular.g } ${ specular.b } 1</color></specular>` +

                            '<shininess>' +

                            (
                                m.specularMap ?
                                    '<texture texture="specular-sampler" texcoord="TEXCOORD" />' :
                                    `<float sid="shininess">${ shininess }</float>`
                            ) +

                            '</shininess>'
                            : ''
                    ) +

                    `<reflective><color>${ diffuse.r } ${ diffuse.g } ${ diffuse.b } 1</color></reflective>` +

                    `<reflectivity><float>${ reflectivity }</float></reflectivity>` +

                    transparencyNode +

                    `</${ type }></technique>`;

                var effectnode =
                    `<effect id="${ matid }-effect">` +
                    '<profile_COMMON>' +

                    (
                        m.map ?
                            '<newparam sid="diffuse-surface"><surface type="2D">' +
                            `<init_from>${ processTexture( m.map ) }</init_from>` +
                            '</surface></newparam>' +
                            '<newparam sid="diffuse-sampler"><sampler2D><source>diffuse-surface</source></sampler2D></newparam>' :
                            ''
                    ) +

                    (
                        m.specularMap ?
                            '<newparam sid="specular-surface"><surface type="2D">' +
                            `<init_from>${ processTexture( m.specularMap ) }</init_from>` +
                            '</surface></newparam>' +
                            '<newparam sid="specular-sampler"><sampler2D><source>specular-surface</source></sampler2D></newparam>' :
                            ''
                    ) +

                    (
                        m.emissiveMap ?
                            '<newparam sid="emissive-surface"><surface type="2D">' +
                            `<init_from>${ processTexture( m.emissiveMap ) }</init_from>` +
                            '</surface></newparam>' +
                            '<newparam sid="emissive-sampler"><sampler2D><source>emissive-surface</source></sampler2D></newparam>' :
                            ''
                    ) +

                    techniqueNode +

                    (
                        m.side === THREE.DoubleSide ?
                            `<extra><technique profile="THREEJS"><double_sided sid="double_sided" type="int">1</double_sided></technique></extra>` :
                            ''
                    ) +

                    '</profile_COMMON>' +

                    '</effect>';

                var materialName = m.name ? ` name="${ m.name }"` : '';
                var materialNode = `<material id="${ matid }"${ materialName }><instance_effect url="#${ matid }-effect" /></material>`;

                libraryMaterials.push( materialNode );
                libraryEffects.push( effectnode );
                materialMap.set( m, matid );

            }

            return matid;

        }

        // Recursively process the object into a scene
        function processObject( o ) {

            var node = `<node name="${ o.name }">`;

            node += getTransform( o );

            if(o.name == 'SizeBox') return '';

            if ( o instanceof THREE.Mesh && o.geometry != null ) {

                // function returns the id associated with the mesh and a "BufferGeometry" version
                // of the geometry in case it's not a geometry.
                var geomInfo = processGeometry( o.geometry );
                var meshid = geomInfo.meshid;
                var geometry = geomInfo.bufferGeometry;

                // ids of the materials to bind to the geometry
                var matids = null;
                var matidsArray = [];

                // get a list of materials to bind to the sub groups of the geometry.
                // If the amount of subgroups is greater than the materials, than reuse
                // the materials.
                var mat = o.material || new THREE.MeshBasicMaterial();
                var materials = Array.isArray( mat ) ? mat : [ mat ];

                if ( geometry.groups.length > materials.length ) {

                    matidsArray = new Array( geometry.groups.length );

                } else {

                    matidsArray = new Array( materials.length );

                }
                matids = matidsArray.fill()
                    .map( ( v, i ) => processMaterial( materials[ i % materials.length ] ) );

                node +=
                    `<instance_geometry url="#${ meshid }">` +

                    (
                        matids != null ?
                            '<bind_material><technique_common>' +
                            matids.map( ( id, i ) =>

                                `<instance_material symbol="MESH_MATERIAL_${ i }" target="#${ id }" >` +

                                '<bind_vertex_input semantic="TEXCOORD" input_semantic="TEXCOORD" input_set="0" />' +

                                '</instance_material>'
                            ).join( '' ) +
                            '</technique_common></bind_material>' :
                            ''
                    ) +

                    '</instance_geometry>';

            }

            o.children.forEach( c => node += processObject( c ) );

            node += '</node>';

            return node;

        }

        var geometryInfo = new WeakMap();
        var materialMap = new WeakMap();
        var imageMap = new WeakMap();
        var textures = [];

        var libraryImages = [];
        var libraryGeometries = [];
        var libraryEffects = [];
        var libraryMaterials = [];
        var libraryVisualScenes = processObject( object );

        var specLink = version === '1.4.1' ? 'http://www.collada.org/2005/11/COLLADASchema' : 'https://www.khronos.org/collada/';
        var dae =
            '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>' +
            `<COLLADA xmlns="${ specLink }" version="${ version }">` +
            '<asset>' +
            (
                '<contributor>' +
                '<authoring_tool>three.js Collada Exporter</authoring_tool>' +
                ( options.author !== null ? `<author>${ options.author }</author>` : '' ) +
                '</contributor>' +
                `<created>${ ( new Date() ).toISOString() }</created>` +
                `<modified>${ ( new Date() ).toISOString() }</modified>` +
                '<up_axis>Y_UP</up_axis>'
            ) +
            '</asset>';

        dae += `<library_images>${ libraryImages.join( '' ) }</library_images>`;

        dae += `<library_effects>${ libraryEffects.join( '' ) }</library_effects>`;

        dae += `<library_materials>${ libraryMaterials.join( '' ) }</library_materials>`;

        dae += `<library_geometries>${ libraryGeometries.join( '' ) }</library_geometries>`;

        dae += `<library_visual_scenes><visual_scene id="Scene" name="scene">${ libraryVisualScenes }</visual_scene></library_visual_scenes>`;

        dae += '<scene><instance_visual_scene url="#Scene"/></scene>';

        dae += '</COLLADA>';

        var res = {
            data: format( dae ),
            textures
        };

        if ( typeof onDone === 'function' ) {

            requestAnimationFrame( () => onDone( res ) );

        }

        return res;

    }

};



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



/**
 * @author fernandojsg / http://fernandojsg.com
 * @author Don McCurdy / https://www.donmccurdy.com
 * @author Takahiro / https://github.com/takahirox
 */

//------------------------------------------------------------------------------
// Constants
//------------------------------------------------------------------------------
var WEBGL_CONSTANTS = {
    POINTS: 0x0000,
    LINES: 0x0001,
    LINE_LOOP: 0x0002,
    LINE_STRIP: 0x0003,
    TRIANGLES: 0x0004,
    TRIANGLE_STRIP: 0x0005,
    TRIANGLE_FAN: 0x0006,

    UNSIGNED_BYTE: 0x1401,
    UNSIGNED_SHORT: 0x1403,
    FLOAT: 0x1406,
    UNSIGNED_INT: 0x1405,
    ARRAY_BUFFER: 0x8892,
    ELEMENT_ARRAY_BUFFER: 0x8893,

    NEAREST: 0x2600,
    LINEAR: 0x2601,
    NEAREST_MIPMAP_NEAREST: 0x2700,
    LINEAR_MIPMAP_NEAREST: 0x2701,
    NEAREST_MIPMAP_LINEAR: 0x2702,
    LINEAR_MIPMAP_LINEAR: 0x2703,

    CLAMP_TO_EDGE: 33071,
    MIRRORED_REPEAT: 33648,
    REPEAT: 10497
};

var THREE_TO_WEBGL = {};

THREE_TO_WEBGL[ THREE.NearestFilter ] = WEBGL_CONSTANTS.NEAREST;
THREE_TO_WEBGL[ THREE.NearestMipMapNearestFilter ] = WEBGL_CONSTANTS.NEAREST_MIPMAP_NEAREST;
THREE_TO_WEBGL[ THREE.NearestMipMapLinearFilter ] = WEBGL_CONSTANTS.NEAREST_MIPMAP_LINEAR;
THREE_TO_WEBGL[ THREE.LinearFilter ] = WEBGL_CONSTANTS.LINEAR;
THREE_TO_WEBGL[ THREE.LinearMipMapNearestFilter ] = WEBGL_CONSTANTS.LINEAR_MIPMAP_NEAREST;
THREE_TO_WEBGL[ THREE.LinearMipMapLinearFilter ] = WEBGL_CONSTANTS.LINEAR_MIPMAP_LINEAR;

THREE_TO_WEBGL[ THREE.ClampToEdgeWrapping ] = WEBGL_CONSTANTS.CLAMP_TO_EDGE;
THREE_TO_WEBGL[ THREE.RepeatWrapping ] = WEBGL_CONSTANTS.REPEAT;
THREE_TO_WEBGL[ THREE.MirroredRepeatWrapping ] = WEBGL_CONSTANTS.MIRRORED_REPEAT;

var PATH_PROPERTIES = {
    scale: 'scale',
    position: 'translation',
    quaternion: 'rotation',
    morphTargetInfluences: 'weights'
};

//------------------------------------------------------------------------------
// GLTF Exporter
//------------------------------------------------------------------------------
THREE.GLTFExporter = function () {};

THREE.GLTFExporter.prototype = {

    constructor: THREE.GLTFExporter,

    /**
     * Parse scenes and generate GLTF output
     * @param  {THREE.Scene or [THREE.Scenes]} input   THREE.Scene or Array of THREE.Scenes
     * @param  {Function} onDone  Callback on completed
     * @param  {Object} options options
     */
    parse: function ( input, onDone, options ) {

        var DEFAULT_OPTIONS = {
            binary: false,
            trs: false,
            onlyVisible: true,
            truncateDrawRange: true,
            embedImages: true,
            animations: [],
            forceIndices: false,
            forcePowerOfTwoTextures: false,
            includeCustomExtensions: false
        };

        options = Object.assign( {}, DEFAULT_OPTIONS, options );

        if ( options.animations.length > 0 ) {

            // Only TRS properties, and not matrices, may be targeted by animation.
            options.trs = true;

        }

        var outputJSON = {

            asset: {

                version: "2.0",
                generator: "THREE.GLTFExporter"

            }

        };

        var byteOffset = 0;
        var buffers = [];
        var pending = [];
        var nodeMap = new Map();
        var skins = [];
        var extensionsUsed = {};
        var cachedData = {

            meshes: new Map(),
            attributes: new Map(),
            attributesNormalized: new Map(),
            materials: new Map(),
            textures: new Map(),
            images: new Map()

        };

        var cachedCanvas;

        var uids = new Map();
        var uid = 0;

        /**
         * Assign and return a temporal unique id for an object
         * especially which doesn't have .uuid
         * @param  {Object} object
         * @return {Integer}
         */
        function getUID( object ) {

            if ( ! uids.has( object ) ) uids.set( object, uid ++ );

            return uids.get( object );

        }

        /**
         * Compare two arrays
         * @param  {Array} array1 Array 1 to compare
         * @param  {Array} array2 Array 2 to compare
         * @return {Boolean}        Returns true if both arrays are equal
         */
        function equalArray( array1, array2 ) {

            return ( array1.length === array2.length ) && array1.every( function ( element, index ) {

                return element === array2[ index ];

            } );

        }

        /**
         * Converts a string to an ArrayBuffer.
         * @param  {string} text
         * @return {ArrayBuffer}
         */
        function stringToArrayBuffer( text ) {

            if ( window.TextEncoder !== undefined ) {

                return new TextEncoder().encode( text ).buffer;

            }

            var array = new Uint8Array( new ArrayBuffer( text.length ) );

            for ( var i = 0, il = text.length; i < il; i ++ ) {

                var value = text.charCodeAt( i );

                // Replacing multi-byte character with space(0x20).
                array[ i ] = value > 0xFF ? 0x20 : value;

            }

            return array.buffer;

        }

        /**
         * Get the min and max vectors from the given attribute
         * @param  {THREE.BufferAttribute} attribute Attribute to find the min/max in range from start to start + count
         * @param  {Integer} start
         * @param  {Integer} count
         * @return {Object} Object containing the `min` and `max` values (As an array of attribute.itemSize components)
         */
        function getMinMax( attribute, start, count ) {

            var output = {

                min: new Array( attribute.itemSize ).fill( Number.POSITIVE_INFINITY ),
                max: new Array( attribute.itemSize ).fill( Number.NEGATIVE_INFINITY )

            };

            for ( var i = start; i < start + count; i ++ ) {

                for ( var a = 0; a < attribute.itemSize; a ++ ) {

                    var value = attribute.array[ i * attribute.itemSize + a ];
                    output.min[ a ] = Math.min( output.min[ a ], value );
                    output.max[ a ] = Math.max( output.max[ a ], value );

                }

            }

            return output;

        }

        /**
         * Checks if image size is POT.
         *
         * @param {Image} image The image to be checked.
         * @returns {Boolean} Returns true if image size is POT.
         *
         */
        function isPowerOfTwo( image ) {

            return THREE.Math.isPowerOfTwo( image.width ) && THREE.Math.isPowerOfTwo( image.height );

        }

        /**
         * Checks if normal attribute values are normalized.
         *
         * @param {THREE.BufferAttribute} normal
         * @returns {Boolean}
         *
         */
        function isNormalizedNormalAttribute( normal ) {

            if ( cachedData.attributesNormalized.has( normal ) ) {

                return false;

            }

            var v = new THREE.Vector3();

            for ( var i = 0, il = normal.count; i < il; i ++ ) {

                // 0.0005 is from glTF-validator
                if ( Math.abs( v.fromArray( normal.array, i * 3 ).length() - 1.0 ) > 0.0005 ) return false;

            }

            return true;

        }

        /**
         * Creates normalized normal buffer attribute.
         *
         * @param {THREE.BufferAttribute} normal
         * @returns {THREE.BufferAttribute}
         *
         */
        function createNormalizedNormalAttribute( normal ) {

            if ( cachedData.attributesNormalized.has( normal ) ) {

                return cachedData.attributesNormalized.get( normal );

            }

            var attribute = normal.clone();

            var v = new THREE.Vector3();

            for ( var i = 0, il = attribute.count; i < il; i ++ ) {

                v.fromArray( attribute.array, i * 3 );

                if ( v.x === 0 && v.y === 0 && v.z === 0 ) {

                    // if values can't be normalized set (1, 0, 0)
                    v.setX( 1.0 );

                } else {

                    v.normalize();

                }

                v.toArray( attribute.array, i * 3 );

            }

            cachedData.attributesNormalized.set( normal, attribute );

            return attribute;

        }

        /**
         * Get the required size + padding for a buffer, rounded to the next 4-byte boundary.
         * https://github.com/KhronosGroup/glTF/tree/master/specification/2.0#data-alignment
         *
         * @param {Integer} bufferSize The size the original buffer.
         * @returns {Integer} new buffer size with required padding.
         *
         */
        function getPaddedBufferSize( bufferSize ) {

            return Math.ceil( bufferSize / 4 ) * 4;

        }

        /**
         * Returns a buffer aligned to 4-byte boundary.
         *
         * @param {ArrayBuffer} arrayBuffer Buffer to pad
         * @param {Integer} paddingByte (Optional)
         * @returns {ArrayBuffer} The same buffer if it's already aligned to 4-byte boundary or a new buffer
         */
        function getPaddedArrayBuffer( arrayBuffer, paddingByte ) {

            paddingByte = paddingByte || 0;

            var paddedLength = getPaddedBufferSize( arrayBuffer.byteLength );

            if ( paddedLength !== arrayBuffer.byteLength ) {

                var array = new Uint8Array( paddedLength );
                array.set( new Uint8Array( arrayBuffer ) );

                if ( paddingByte !== 0 ) {

                    for ( var i = arrayBuffer.byteLength; i < paddedLength; i ++ ) {

                        array[ i ] = paddingByte;

                    }

                }

                return array.buffer;

            }

            return arrayBuffer;

        }

        /**
         * Serializes a userData.
         *
         * @param {THREE.Object3D|THREE.Material} object
         * @param {Object} gltfProperty
         */
        function serializeUserData( object, gltfProperty ) {

            if ( Object.keys( object.userData ).length === 0 ) {

                return;

            }

            try {

                var json = JSON.parse( JSON.stringify( object.userData ) );

                if ( options.includeCustomExtensions && json.gltfExtensions ) {

                    if ( gltfProperty.extensions === undefined ) {

                        gltfProperty.extensions = {};

                    }

                    for ( var extensionName in json.gltfExtensions ) {

                        gltfProperty.extensions[ extensionName ] = json.gltfExtensions[ extensionName ];
                        extensionsUsed[ extensionName ] = true;

                    }

                    delete json.gltfExtensions;

                }

                if ( Object.keys( json ).length > 0 ) {

                    gltfProperty.extras = json;

                }

            } catch ( error ) {

                console.warn( 'THREE.GLTFExporter: userData of \'' + object.name + '\' ' +
                    'won\'t be serialized because of JSON.stringify error - ' + error.message );

            }

        }

        /**
         * Applies a texture transform, if present, to the map definition. Requires
         * the KHR_texture_transform extension.
         */
        function applyTextureTransform( mapDef, texture ) {

            var didTransform = false;
            var transformDef = {};

            if ( texture.offset.x !== 0 || texture.offset.y !== 0 ) {

                transformDef.offset = texture.offset.toArray();
                didTransform = true;

            }

            if ( texture.rotation !== 0 ) {

                transformDef.rotation = texture.rotation;
                didTransform = true;

            }

            if ( texture.repeat.x !== 1 || texture.repeat.y !== 1 ) {

                transformDef.scale = texture.repeat.toArray();
                didTransform = true;

            }

            if ( didTransform ) {

                mapDef.extensions = mapDef.extensions || {};
                mapDef.extensions[ 'KHR_texture_transform' ] = transformDef;
                extensionsUsed[ 'KHR_texture_transform' ] = true;

            }

        }

        /**
         * Process a buffer to append to the default one.
         * @param  {ArrayBuffer} buffer
         * @return {Integer}
         */
        function processBuffer( buffer ) {

            if ( ! outputJSON.buffers ) {

                outputJSON.buffers = [ { byteLength: 0 } ];

            }

            // All buffers are merged before export.
            buffers.push( buffer );

            return 0;

        }

        /**
         * Process and generate a BufferView
         * @param  {THREE.BufferAttribute} attribute
         * @param  {number} componentType
         * @param  {number} start
         * @param  {number} count
         * @param  {number} target (Optional) Target usage of the BufferView
         * @return {Object}
         */
        function processBufferView( attribute, componentType, start, count, target ) {

            if ( ! outputJSON.bufferViews ) {

                outputJSON.bufferViews = [];

            }

            // Create a new dataview and dump the attribute's array into it

            var componentSize;

            if ( componentType === WEBGL_CONSTANTS.UNSIGNED_BYTE ) {

                componentSize = 1;

            } else if ( componentType === WEBGL_CONSTANTS.UNSIGNED_SHORT ) {

                componentSize = 2;

            } else {

                componentSize = 4;

            }

            var byteLength = getPaddedBufferSize( count * attribute.itemSize * componentSize );
            var dataView = new DataView( new ArrayBuffer( byteLength ) );
            var offset = 0;

            for ( var i = start; i < start + count; i ++ ) {

                for ( var a = 0; a < attribute.itemSize; a ++ ) {

                    // @TODO Fails on InterleavedBufferAttribute, and could probably be
                    // optimized for normal BufferAttribute.
                    var value = attribute.array[ i * attribute.itemSize + a ];

                    if ( componentType === WEBGL_CONSTANTS.FLOAT ) {

                        dataView.setFloat32( offset, value, true );

                    } else if ( componentType === WEBGL_CONSTANTS.UNSIGNED_INT ) {

                        dataView.setUint32( offset, value, true );

                    } else if ( componentType === WEBGL_CONSTANTS.UNSIGNED_SHORT ) {

                        dataView.setUint16( offset, value, true );

                    } else if ( componentType === WEBGL_CONSTANTS.UNSIGNED_BYTE ) {

                        dataView.setUint8( offset, value );

                    }

                    offset += componentSize;

                }

            }

            var gltfBufferView = {

                buffer: processBuffer( dataView.buffer ),
                byteOffset: byteOffset,
                byteLength: byteLength

            };

            if ( target !== undefined ) gltfBufferView.target = target;

            if ( target === WEBGL_CONSTANTS.ARRAY_BUFFER ) {

                // Only define byteStride for vertex attributes.
                gltfBufferView.byteStride = attribute.itemSize * componentSize;

            }

            byteOffset += byteLength;

            outputJSON.bufferViews.push( gltfBufferView );

            // @TODO Merge bufferViews where possible.
            var output = {

                id: outputJSON.bufferViews.length - 1,
                byteLength: 0

            };

            return output;

        }

        /**
         * Process and generate a BufferView from an image Blob.
         * @param {Blob} blob
         * @return {Promise<Integer>}
         */
        function processBufferViewImage( blob ) {

            if ( ! outputJSON.bufferViews ) {

                outputJSON.bufferViews = [];

            }

            return new Promise( function ( resolve ) {

                var reader = new window.FileReader();
                reader.readAsArrayBuffer( blob );
                reader.onloadend = function () {

                    var buffer = getPaddedArrayBuffer( reader.result );

                    var bufferView = {
                        buffer: processBuffer( buffer ),
                        byteOffset: byteOffset,
                        byteLength: buffer.byteLength
                    };

                    byteOffset += buffer.byteLength;

                    outputJSON.bufferViews.push( bufferView );

                    resolve( outputJSON.bufferViews.length - 1 );

                };

            } );

        }

        /**
         * Process attribute to generate an accessor
         * @param  {THREE.BufferAttribute} attribute Attribute to process
         * @param  {THREE.BufferGeometry} geometry (Optional) Geometry used for truncated draw range
         * @param  {Integer} start (Optional)
         * @param  {Integer} count (Optional)
         * @return {Integer}           Index of the processed accessor on the "accessors" array
         */
        function processAccessor( attribute, geometry, start, count ) {

            var types = {

                1: 'SCALAR',
                2: 'VEC2',
                3: 'VEC3',
                4: 'VEC4',
                16: 'MAT4'

            };

            var componentType;

            // Detect the component type of the attribute array (float, uint or ushort)
            if ( attribute.array.constructor === Float32Array ) {

                componentType = WEBGL_CONSTANTS.FLOAT;

            } else if ( attribute.array.constructor === Uint32Array ) {

                componentType = WEBGL_CONSTANTS.UNSIGNED_INT;

            } else if ( attribute.array.constructor === Uint16Array ) {

                componentType = WEBGL_CONSTANTS.UNSIGNED_SHORT;

            } else if ( attribute.array.constructor === Uint8Array ) {

                componentType = WEBGL_CONSTANTS.UNSIGNED_BYTE;

            } else {

                throw new Error( 'THREE.GLTFExporter: Unsupported bufferAttribute component type.' );

            }

            if ( start === undefined ) start = 0;
            if ( count === undefined ) count = attribute.count;

            // @TODO Indexed buffer geometry with drawRange not supported yet
            if ( options.truncateDrawRange && geometry !== undefined && geometry.index === null ) {

                var end = start + count;
                var end2 = geometry.drawRange.count === Infinity
                    ? attribute.count
                    : geometry.drawRange.start + geometry.drawRange.count;

                start = Math.max( start, geometry.drawRange.start );
                count = Math.min( end, end2 ) - start;

                if ( count < 0 ) count = 0;

            }

            // Skip creating an accessor if the attribute doesn't have data to export
            if ( count === 0 ) {

                return null;

            }

            var minMax = getMinMax( attribute, start, count );

            var bufferViewTarget;

            // If geometry isn't provided, don't infer the target usage of the bufferView. For
            // animation samplers, target must not be set.
            if ( geometry !== undefined ) {

                bufferViewTarget = attribute === geometry.index ? WEBGL_CONSTANTS.ELEMENT_ARRAY_BUFFER : WEBGL_CONSTANTS.ARRAY_BUFFER;

            }

            var bufferView = processBufferView( attribute, componentType, start, count, bufferViewTarget );

            var gltfAccessor = {

                bufferView: bufferView.id,
                byteOffset: bufferView.byteOffset,
                componentType: componentType,
                count: count,
                max: minMax.max,
                min: minMax.min,
                type: types[ attribute.itemSize ]

            };

            if ( ! outputJSON.accessors ) {

                outputJSON.accessors = [];

            }

            outputJSON.accessors.push( gltfAccessor );

            return outputJSON.accessors.length - 1;

        }

        /**
         * Process image
         * @param  {Image} image to process
         * @param  {Integer} format of the image (e.g. THREE.RGBFormat, THREE.RGBAFormat etc)
         * @param  {Boolean} flipY before writing out the image
         * @return {Integer}     Index of the processed texture in the "images" array
         */
        function processImage( image, format, flipY ) {

            if ( ! cachedData.images.has( image ) ) {

                cachedData.images.set( image, {} );

            }

            var cachedImages = cachedData.images.get( image );
            var mimeType = format === THREE.RGBAFormat ? 'image/png' : 'image/jpeg';
            var key = mimeType + ":flipY/" + flipY.toString();

            if ( cachedImages[ key ] !== undefined ) {

                return cachedImages[ key ];

            }

            if ( ! outputJSON.images ) {

                outputJSON.images = [];

            }

            var gltfImage = { mimeType: mimeType };

            if ( options.embedImages ) {

                var canvas = cachedCanvas = cachedCanvas || document.createElement( 'canvas' );

                canvas.width = image.width;
                canvas.height = image.height;

                if ( options.forcePowerOfTwoTextures && ! isPowerOfTwo( image ) ) {

                    console.warn( 'GLTFExporter: Resized non-power-of-two image.', image );

                    canvas.width = THREE.Math.floorPowerOfTwo( canvas.width );
                    canvas.height = THREE.Math.floorPowerOfTwo( canvas.height );

                }

                var ctx = canvas.getContext( '2d' );

                if ( flipY === true ) {

                    ctx.translate( 0, canvas.height );
                    ctx.scale( 1, - 1 );

                }

                ctx.drawImage( image, 0, 0, canvas.width, canvas.height );

                if ( options.binary === true ) {

                    pending.push( new Promise( function ( resolve ) {

                        canvas.toBlob( function ( blob ) {

                            processBufferViewImage( blob ).then( function ( bufferViewIndex ) {

                                gltfImage.bufferView = bufferViewIndex;

                                resolve();

                            } );

                        }, mimeType );

                    } ) );

                } else {

                    gltfImage.uri = canvas.toDataURL( mimeType );

                }

            } else {

                gltfImage.uri = image.src;

            }

            outputJSON.images.push( gltfImage );

            var index = outputJSON.images.length - 1;
            cachedImages[ key ] = index;

            return index;

        }

        /**
         * Process sampler
         * @param  {Texture} map Texture to process
         * @return {Integer}     Index of the processed texture in the "samplers" array
         */
        function processSampler( map ) {

            if ( ! outputJSON.samplers ) {

                outputJSON.samplers = [];

            }

            var gltfSampler = {

                magFilter: THREE_TO_WEBGL[ map.magFilter ],
                minFilter: THREE_TO_WEBGL[ map.minFilter ],
                wrapS: THREE_TO_WEBGL[ map.wrapS ],
                wrapT: THREE_TO_WEBGL[ map.wrapT ]

            };

            outputJSON.samplers.push( gltfSampler );

            return outputJSON.samplers.length - 1;

        }

        /**
         * Process texture
         * @param  {Texture} map Map to process
         * @return {Integer}     Index of the processed texture in the "textures" array
         */
        function processTexture( map ) {

            if ( cachedData.textures.has( map ) ) {

                return cachedData.textures.get( map );

            }

            if ( ! outputJSON.textures ) {

                outputJSON.textures = [];

            }

            var gltfTexture = {

                sampler: processSampler( map ),
                source: processImage( map.image, map.format, map.flipY )

            };

            outputJSON.textures.push( gltfTexture );

            var index = outputJSON.textures.length - 1;
            cachedData.textures.set( map, index );

            return index;

        }

        /**
         * Process material
         * @param  {THREE.Material} material Material to process
         * @return {Integer}      Index of the processed material in the "materials" array
         */
        function processMaterial( material ) {

            if ( cachedData.materials.has( material ) ) {

                return cachedData.materials.get( material );

            }

            if ( ! outputJSON.materials ) {

                outputJSON.materials = [];

            }

            if ( material.isShaderMaterial ) {

                console.warn( 'GLTFExporter: THREE.ShaderMaterial not supported.' );
                return null;

            }

            // @QUESTION Should we avoid including any attribute that has the default value?
            var gltfMaterial = {

                pbrMetallicRoughness: {}

            };

            if ( material.isMeshBasicMaterial ) {

                gltfMaterial.extensions = { KHR_materials_unlit: {} };

                extensionsUsed[ 'KHR_materials_unlit' ] = true;

            } else if ( ! material.isMeshStandardMaterial ) {

                console.warn( 'GLTFExporter: Use MeshStandardMaterial or MeshBasicMaterial for best results.' );

            }

            // pbrMetallicRoughness.baseColorFactor
            var color = material.color.toArray().concat( [ material.opacity ] );

            if ( ! equalArray( color, [ 1, 1, 1, 1 ] ) ) {

                gltfMaterial.pbrMetallicRoughness.baseColorFactor = color;

            }

            if ( material.isMeshStandardMaterial ) {

                gltfMaterial.pbrMetallicRoughness.metallicFactor = material.metalness;
                gltfMaterial.pbrMetallicRoughness.roughnessFactor = material.roughness;

            } else if ( material.isMeshBasicMaterial ) {

                gltfMaterial.pbrMetallicRoughness.metallicFactor = 0.0;
                gltfMaterial.pbrMetallicRoughness.roughnessFactor = 0.9;

            } else {

                gltfMaterial.pbrMetallicRoughness.metallicFactor = 0.5;
                gltfMaterial.pbrMetallicRoughness.roughnessFactor = 0.5;

            }

            // pbrMetallicRoughness.metallicRoughnessTexture
            if ( material.metalnessMap || material.roughnessMap ) {

                if ( material.metalnessMap === material.roughnessMap ) {

                    var metalRoughMapDef = { index: processTexture( material.metalnessMap ) };
                    applyTextureTransform( metalRoughMapDef, material.metalnessMap );
                    gltfMaterial.pbrMetallicRoughness.metallicRoughnessTexture = metalRoughMapDef;

                } else {

                    console.warn( 'THREE.GLTFExporter: Ignoring metalnessMap and roughnessMap because they are not the same Texture.' );

                }

            }

            // pbrMetallicRoughness.baseColorTexture
            if ( material.map ) {

                var baseColorMapDef = { index: processTexture( material.map ) };
                applyTextureTransform( baseColorMapDef, material.map );
                gltfMaterial.pbrMetallicRoughness.baseColorTexture = baseColorMapDef;

            }

            if ( material.isMeshBasicMaterial ||
                material.isLineBasicMaterial ||
                material.isPointsMaterial ) {

            } else {

                // emissiveFactor
                var emissive = material.emissive.clone().multiplyScalar( material.emissiveIntensity ).toArray();

                if ( ! equalArray( emissive, [ 0, 0, 0 ] ) ) {

                    gltfMaterial.emissiveFactor = emissive;

                }

                // emissiveTexture
                if ( material.emissiveMap ) {

                    var emissiveMapDef = { index: processTexture( material.emissiveMap ) };
                    applyTextureTransform( emissiveMapDef, material.emissiveMap );
                    gltfMaterial.emissiveTexture = emissiveMapDef;

                }

            }

            // normalTexture
            if ( material.normalMap ) {

                var normalMapDef = { index: processTexture( material.normalMap ) };

                if ( material.normalScale.x !== - 1 ) {

                    if ( material.normalScale.x !== material.normalScale.y ) {

                        console.warn( 'THREE.GLTFExporter: Normal scale components are different, ignoring Y and exporting X.' );

                    }

                    normalMapDef.scale = material.normalScale.x;

                }

                applyTextureTransform( normalMapDef, material.normalMap );

                gltfMaterial.normalTexture = normalMapDef;

            }

            // occlusionTexture
            if ( material.aoMap ) {

                var occlusionMapDef = {
                    index: processTexture( material.aoMap ),
                    texCoord: 1
                };

                if ( material.aoMapIntensity !== 1.0 ) {

                    occlusionMapDef.strength = material.aoMapIntensity;

                }

                applyTextureTransform( occlusionMapDef, material.aoMap );

                gltfMaterial.occlusionTexture = occlusionMapDef;

            }

            // alphaMode
            if ( material.transparent || material.alphaTest > 0.0 ) {

                gltfMaterial.alphaMode = material.opacity < 1.0 ? 'BLEND' : 'MASK';

                // Write alphaCutoff if it's non-zero and different from the default (0.5).
                if ( material.alphaTest > 0.0 && material.alphaTest !== 0.5 ) {

                    gltfMaterial.alphaCutoff = material.alphaTest;

                }

            }

            // doubleSided
            if ( material.side === THREE.DoubleSide ) {

                gltfMaterial.doubleSided = true;

            }

            if ( material.name !== '' ) {

                gltfMaterial.name = material.name;

            }

            serializeUserData( material, gltfMaterial );

            outputJSON.materials.push( gltfMaterial );

            var index = outputJSON.materials.length - 1;
            cachedData.materials.set( material, index );

            return index;

        }

        /**
         * Process mesh
         * @param  {THREE.Mesh} mesh Mesh to process
         * @return {Integer}      Index of the processed mesh in the "meshes" array
         */
        function processMesh( mesh ) {

            var cacheKey = mesh.geometry.uuid + ':' + mesh.material.uuid;
            if ( cachedData.meshes.has( cacheKey ) ) {

                return cachedData.meshes.get( cacheKey );

            }

            if(!mesh.geometry.isBufferGeometry ){
                var geometry = new THREE.BufferGeometry().fromGeometry(mesh.geometry);
            } else {
                var geometry = mesh.geometry;
            }


            var mode;

            // Use the correct mode
            if ( mesh.isLineSegments ) {

                mode = WEBGL_CONSTANTS.LINES;

            } else if ( mesh.isLineLoop ) {

                mode = WEBGL_CONSTANTS.LINE_LOOP;

            } else if ( mesh.isLine ) {

                mode = WEBGL_CONSTANTS.LINE_STRIP;

            } else if ( mesh.isPoints ) {

                mode = WEBGL_CONSTANTS.POINTS;

            } else {

                if ( ! geometry.isBufferGeometry ) {

                    console.warn( 'GLTFExporter: Exporting THREE.Geometry will increase file size. Use THREE.BufferGeometry instead.' );

                    var geometryTemp = new THREE.BufferGeometry();
                    geometryTemp.fromGeometry( geometry );
                    geometry = geometryTemp;

                }

                // if ( mesh.drawMode === THREE.TriangleFanDrawMode ) {
                //
                //     console.warn( 'GLTFExporter: TriangleFanDrawMode and wireframe incompatible.' );
                //     mode = WEBGL_CONSTANTS.TRIANGLE_FAN;
                //
                // } else if ( mesh.drawMode === THREE.TriangleStripDrawMode ) {
                //
                //     mode = mesh.material.wireframe ? WEBGL_CONSTANTS.LINE_STRIP : WEBGL_CONSTANTS.TRIANGLE_STRIP;
                //
                // } else {
                //
                //     mode = mesh.material.wireframe ? WEBGL_CONSTANTS.LINES : WEBGL_CONSTANTS.TRIANGLES;
                //
                // }

            }

            var gltfMesh = {};

            var attributes = {};
            var primitives = [];
            var targets = [];

            // Conversion between attributes names in threejs and gltf spec
            var nameConversion = {

                uv: 'TEXCOORD_0',
                uv2: 'TEXCOORD_1',
                color: 'COLOR_0',
                skinWeight: 'WEIGHTS_0',
                skinIndex: 'JOINTS_0'

            };

            var originalNormal = geometry.getAttribute( 'normal' );

            if ( originalNormal !== undefined && ! isNormalizedNormalAttribute( originalNormal ) ) {

                console.warn( 'THREE.GLTFExporter: Creating normalized normal attribute from the non-normalized one.' );

                geometry.addAttribute( 'normal', createNormalizedNormalAttribute( originalNormal ) );

            }

            // @QUESTION Detect if .vertexColors = THREE.VertexColors?
            // For every attribute create an accessor
            var modifiedAttribute = null;
            for ( var attributeName in geometry.attributes ) {

                // Ignore morph target attributes, which are exported later.
                if ( attributeName.substr( 0, 5 ) === 'morph' ) continue;

                var attribute = geometry.attributes[ attributeName ];
                attributeName = nameConversion[ attributeName ] || attributeName.toUpperCase();

                // Prefix all geometry attributes except the ones specifically
                // listed in the spec; non-spec attributes are considered custom.
                var validVertexAttributes =
                    /^(POSITION|NORMAL|TANGENT|TEXCOORD_\d+|COLOR_\d+|JOINTS_\d+|WEIGHTS_\d+)$/;
                if ( ! validVertexAttributes.test( attributeName ) ) {

                    attributeName = '_' + attributeName;

                }

                if ( cachedData.attributes.has( getUID( attribute ) ) ) {

                    attributes[ attributeName ] = cachedData.attributes.get( getUID( attribute ) );
                    continue;

                }

                // JOINTS_0 must be UNSIGNED_BYTE or UNSIGNED_SHORT.
                modifiedAttribute = null;
                var array = attribute.array;
                if ( attributeName === 'JOINTS_0' &&
                    ! ( array instanceof Uint16Array ) &&
                    ! ( array instanceof Uint8Array ) ) {

                    console.warn( 'GLTFExporter: Attribute "skinIndex" converted to type UNSIGNED_SHORT.' );
                    modifiedAttribute = new THREE.BufferAttribute( new Uint16Array( array ), attribute.itemSize, attribute.normalized );

                }

                var accessor = processAccessor( modifiedAttribute || attribute, geometry );
                if ( accessor !== null ) {

                    attributes[ attributeName ] = accessor;
                    cachedData.attributes.set( getUID( attribute ), accessor );

                }

            }

            if ( originalNormal !== undefined ) geometry.addAttribute( 'normal', originalNormal );

            // Skip if no exportable attributes found
            if ( Object.keys( attributes ).length === 0 ) {

                return null;

            }

            // Morph targets
            if ( mesh.morphTargetInfluences !== undefined && mesh.morphTargetInfluences.length > 0 ) {

                var weights = [];
                var targetNames = [];
                var reverseDictionary = {};

                if ( mesh.morphTargetDictionary !== undefined ) {

                    for ( var key in mesh.morphTargetDictionary ) {

                        reverseDictionary[ mesh.morphTargetDictionary[ key ] ] = key;

                    }

                }

                for ( var i = 0; i < mesh.morphTargetInfluences.length; ++ i ) {

                    var target = {};

                    var warned = false;

                    for ( var attributeName in geometry.morphAttributes ) {

                        // glTF 2.0 morph supports only POSITION/NORMAL/TANGENT.
                        // Three.js doesn't support TANGENT yet.

                        if ( attributeName !== 'position' && attributeName !== 'normal' ) {

                            if ( ! warned ) {

                                console.warn( 'GLTFExporter: Only POSITION and NORMAL morph are supported.' );
                                warned = true;

                            }

                            continue;

                        }

                        var attribute = geometry.morphAttributes[ attributeName ][ i ];
                        var gltfAttributeName = attributeName.toUpperCase();

                        // Three.js morph attribute has absolute values while the one of glTF has relative values.
                        //
                        // glTF 2.0 Specification:
                        // https://github.com/KhronosGroup/glTF/tree/master/specification/2.0#morph-targets

                        var baseAttribute = geometry.attributes[ attributeName ];

                        if ( cachedData.attributes.has( getUID( attribute ) ) ) {

                            target[ gltfAttributeName ] = cachedData.attributes.get( getUID( attribute ) );
                            continue;

                        }

                        // Clones attribute not to override
                        var relativeAttribute = attribute.clone();

                        for ( var j = 0, jl = attribute.count; j < jl; j ++ ) {

                            relativeAttribute.setXYZ(
                                j,
                                attribute.getX( j ) - baseAttribute.getX( j ),
                                attribute.getY( j ) - baseAttribute.getY( j ),
                                attribute.getZ( j ) - baseAttribute.getZ( j )
                            );

                        }

                        target[ gltfAttributeName ] = processAccessor( relativeAttribute, geometry );
                        cachedData.attributes.set( getUID( baseAttribute ), target[ gltfAttributeName ] );

                    }

                    targets.push( target );

                    weights.push( mesh.morphTargetInfluences[ i ] );
                    if ( mesh.morphTargetDictionary !== undefined ) targetNames.push( reverseDictionary[ i ] );

                }

                gltfMesh.weights = weights;

                if ( targetNames.length > 0 ) {

                    gltfMesh.extras = {};
                    gltfMesh.extras.targetNames = targetNames;

                }

            }

            var forceIndices = options.forceIndices;
            var isMultiMaterial = Array.isArray( mesh.material );

            if ( isMultiMaterial && geometry.groups.length === 0 ) return null;

            if ( ! forceIndices && geometry.index === null && isMultiMaterial ) {

                // temporal workaround.
                console.warn( 'THREE.GLTFExporter: Creating index for non-indexed multi-material mesh.' );
                forceIndices = true;

            }

            var didForceIndices = false;

            if ( geometry.index === null && forceIndices ) {

                var indices = [];

                for ( var i = 0, il = geometry.attributes.position.count; i < il; i ++ ) {

                    indices[ i ] = i;

                }

                geometry.setIndex( indices );

                didForceIndices = true;

            }

            var materials = isMultiMaterial ? mesh.material : [ mesh.material ];
            var groups = isMultiMaterial ? geometry.groups : [ { materialIndex: 0, start: undefined, count: undefined } ];

            for ( var i = 0, il = groups.length; i < il; i ++ ) {

                var primitive = {
                    mode: mode,
                    attributes: attributes,
                };

                serializeUserData( geometry, primitive );

                if ( targets.length > 0 ) primitive.targets = targets;

                if ( geometry.index !== null ) {

                    var cacheKey = getUID( geometry.index );

                    if ( groups[ i ].start !== undefined || groups[ i ].count !== undefined ) {

                        cacheKey += ':' + groups[ i ].start + ':' + groups[ i ].count;

                    }

                    if ( cachedData.attributes.has( cacheKey ) ) {

                        primitive.indices = cachedData.attributes.get( cacheKey );

                    } else {

                        primitive.indices = processAccessor( geometry.index, geometry, groups[ i ].start, groups[ i ].count );
                        cachedData.attributes.set( cacheKey, primitive.indices );

                    }

                    if ( primitive.indices === null ) delete primitive.indices;

                }

                var material = processMaterial( materials[ groups[ i ].materialIndex ] );

                if ( material !== null ) {

                    primitive.material = material;

                }

                primitives.push( primitive );

            }

            if ( didForceIndices ) {

                geometry.setIndex( null );

            }

            gltfMesh.primitives = primitives;

            if ( ! outputJSON.meshes ) {

                outputJSON.meshes = [];

            }

            outputJSON.meshes.push( gltfMesh );

            var index = outputJSON.meshes.length - 1;
            cachedData.meshes.set( cacheKey, index );

            return index;

        }

        /**
         * Process camera
         * @param  {THREE.Camera} camera Camera to process
         * @return {Integer}      Index of the processed mesh in the "camera" array
         */
        function processCamera( camera ) {

            if ( ! outputJSON.cameras ) {

                outputJSON.cameras = [];

            }

            var isOrtho = camera.isOrthographicCamera;

            var gltfCamera = {

                type: isOrtho ? 'orthographic' : 'perspective'

            };

            if ( isOrtho ) {

                gltfCamera.orthographic = {

                    xmag: camera.right * 2,
                    ymag: camera.top * 2,
                    zfar: camera.far <= 0 ? 0.001 : camera.far,
                    znear: camera.near < 0 ? 0 : camera.near

                };

            } else {

                gltfCamera.perspective = {

                    aspectRatio: camera.aspect,
                    yfov: THREE.Math.degToRad( camera.fov ),
                    zfar: camera.far <= 0 ? 0.001 : camera.far,
                    znear: camera.near < 0 ? 0 : camera.near

                };

            }

            if ( camera.name !== '' ) {

                gltfCamera.name = camera.type;

            }

            outputJSON.cameras.push( gltfCamera );

            return outputJSON.cameras.length - 1;

        }

        /**
         * Creates glTF animation entry from AnimationClip object.
         *
         * Status:
         * - Only properties listed in PATH_PROPERTIES may be animated.
         *
         * @param {THREE.AnimationClip} clip
         * @param {THREE.Object3D} root
         * @return {number}
         */
        function processAnimation( clip, root ) {

            if ( ! outputJSON.animations ) {

                outputJSON.animations = [];

            }

            clip = THREE.GLTFExporter.Utils.mergeMorphTargetTracks( clip.clone(), root );

            var tracks = clip.tracks;
            var channels = [];
            var samplers = [];

            for ( var i = 0; i < tracks.length; ++ i ) {

                var track = tracks[ i ];
                var trackBinding = THREE.PropertyBinding.parseTrackName( track.name );
                var trackNode = THREE.PropertyBinding.findNode( root, trackBinding.nodeName );
                var trackProperty = PATH_PROPERTIES[ trackBinding.propertyName ];

                if ( trackBinding.objectName === 'bones' ) {

                    if ( trackNode.isSkinnedMesh === true ) {

                        trackNode = trackNode.skeleton.getBoneByName( trackBinding.objectIndex );

                    } else {

                        trackNode = undefined;

                    }

                }

                if ( ! trackNode || ! trackProperty ) {

                    console.warn( 'THREE.GLTFExporter: Could not export animation track "%s".', track.name );
                    return null;

                }

                var inputItemSize = 1;
                var outputItemSize = track.values.length / track.times.length;

                if ( trackProperty === PATH_PROPERTIES.morphTargetInfluences ) {

                    outputItemSize /= trackNode.morphTargetInfluences.length;

                }

                var interpolation;

                // @TODO export CubicInterpolant(InterpolateSmooth) as CUBICSPLINE

                // Detecting glTF cubic spline interpolant by checking factory method's special property
                // GLTFCubicSplineInterpolant is a custom interpolant and track doesn't return
                // valid value from .getInterpolation().
                if ( track.createInterpolant.isInterpolantFactoryMethodGLTFCubicSpline === true ) {

                    interpolation = 'CUBICSPLINE';

                    // itemSize of CUBICSPLINE keyframe is 9
                    // (VEC3 * 3: inTangent, splineVertex, and outTangent)
                    // but needs to be stored as VEC3 so dividing by 3 here.
                    outputItemSize /= 3;

                } else if ( track.getInterpolation() === THREE.InterpolateDiscrete ) {

                    interpolation = 'STEP';

                } else {

                    interpolation = 'LINEAR';

                }

                samplers.push( {

                    input: processAccessor( new THREE.BufferAttribute( track.times, inputItemSize ) ),
                    output: processAccessor( new THREE.BufferAttribute( track.values, outputItemSize ) ),
                    interpolation: interpolation

                } );

                channels.push( {

                    sampler: samplers.length - 1,
                    target: {
                        node: nodeMap.get( trackNode ),
                        path: trackProperty
                    }

                } );

            }

            outputJSON.animations.push( {

                name: clip.name || 'clip_' + outputJSON.animations.length,
                samplers: samplers,
                channels: channels

            } );

            return outputJSON.animations.length - 1;

        }

        function processSkin( object ) {

            var node = outputJSON.nodes[ nodeMap.get( object ) ];

            var skeleton = object.skeleton;
            var rootJoint = object.skeleton.bones[ 0 ];

            if ( rootJoint === undefined ) return null;

            var joints = [];
            var inverseBindMatrices = new Float32Array( skeleton.bones.length * 16 );

            for ( var i = 0; i < skeleton.bones.length; ++ i ) {

                joints.push( nodeMap.get( skeleton.bones[ i ] ) );

                skeleton.boneInverses[ i ].toArray( inverseBindMatrices, i * 16 );

            }

            if ( outputJSON.skins === undefined ) {

                outputJSON.skins = [];

            }

            outputJSON.skins.push( {

                inverseBindMatrices: processAccessor( new THREE.BufferAttribute( inverseBindMatrices, 16 ) ),
                joints: joints,
                skeleton: nodeMap.get( rootJoint )

            } );

            var skinIndex = node.skin = outputJSON.skins.length - 1;

            return skinIndex;

        }

        function processLight( light ) {

            var lightDef = {};

            if ( light.name ) lightDef.name = light.name;

            lightDef.color = light.color.toArray();

            lightDef.intensity = light.intensity;

            if ( light.isDirectionalLight ) {

                lightDef.type = 'directional';

            } else if ( light.isPointLight ) {

                lightDef.type = 'point';
                if ( light.distance > 0 ) lightDef.range = light.distance;

            } else if ( light.isSpotLight ) {

                lightDef.type = 'spot';
                if ( light.distance > 0 ) lightDef.range = light.distance;
                lightDef.spot = {};
                lightDef.spot.innerConeAngle = ( light.penumbra - 1.0 ) * light.angle * - 1.0;
                lightDef.spot.outerConeAngle = light.angle;

            }

            if ( light.decay !== undefined && light.decay !== 2 ) {

                console.warn( 'THREE.GLTFExporter: Light decay may be lost. glTF is physically-based, '
                    + 'and expects light.decay=2.' );

            }

            if ( light.target
                && ( light.target.parent !== light
                    || light.target.position.x !== 0
                    || light.target.position.y !== 0
                    || light.target.position.z !== - 1 ) ) {

                console.warn( 'THREE.GLTFExporter: Light direction may be lost. For best results, '
                    + 'make light.target a child of the light with position 0,0,-1.' );

            }

            var lights = outputJSON.extensions[ 'KHR_lights_punctual' ].lights;
            lights.push( lightDef );
            return lights.length - 1;

        }

        /**
         * Process Object3D node
         * @param  {THREE.Object3D} node Object3D to processNode
         * @return {Integer}      Index of the node in the nodes list
         */
        function processNode( object ) {



            if ( ! outputJSON.nodes ) {
                outputJSON.nodes = [];
            }

            if(object.isGroup){
                if(!object.children.length) return null;
            }

            if(object.isGroup){
                if(!check_group(object)) return null;
            }

            if(object.parent.uuid === scene.uuid){
                if(object.name != 'Room') return null;
            }

            if(object.parent.uuid === room.uuid){
                if(
                    object.name == 'Sizes' ||
                    object.name == 'Lights' ||
                    object.name == 'Common_sizes'
                ) return null;
            }


            if(object.type == 'Sizes_obj') return null;
            if(object.name == 'Sizes') return null;
            if(object.name == 'Sizes from wall') return null;
            if(object.name === 'cut') return null;


            if(object.parent.type == 'Cabinet'){
                if(object.name == 'Lights') return null;
            }











            var gltfNode = {};

            if ( options.trs ) {

                var rotation = object.quaternion.toArray();
                var position = object.position.toArray();
                var scale = object.scale.toArray();

                if ( ! equalArray( rotation, [ 0, 0, 0, 1 ] ) ) {

                    gltfNode.rotation = rotation;

                }

                if ( ! equalArray( position, [ 0, 0, 0 ] ) ) {

                    gltfNode.translation = position;

                }

                if ( ! equalArray( scale, [ 1, 1, 1 ] ) ) {

                    gltfNode.scale = scale;

                }

            } else {

                if ( object.matrixAutoUpdate ) {

                    object.updateMatrix();

                }

                if ( ! equalArray( object.matrix.elements, [ 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1 ] ) ) {

                    gltfNode.matrix = object.matrix.elements;

                }

            }

            // We don't export empty strings name because it represents no-name in Three.js.
            if ( object.name !== '' ) {

                gltfNode.name = String( object.name + '_' + object.uuid );


                // console.log(object)






            }

            if(object.parent.uuid === room.walls.uuid){

                let name = '';

                if(object.name === "floor") name = parseInt(room.params.width) + "," + parseInt(room.params.depth) + ";" + project_settings.selected_materials.floor + ";" + object.name;
                if(object.name === "ceiling") name = object.name;
                if(object.name === 'front_wall' || object.name === 'back_wall') name = parseInt(room.params.width) + "," + parseInt(room.params.height) + ";" + room.params.wall_material + ";" + object.name;
                if(object.name === 'left_wall' || object.name === 'right_wall') name = parseInt(room.params.depth) + "," + parseInt(room.params.height) + ";" + room.params.wall_material + ";" + object.name;

                gltfNode.name = name;


            }

            if(object.parent && object.parent.type === 'Shaft'){
                let name = '';

                if(object.parent.params.width > object.parent.params.depth){
                    name = parseInt(object.parent.params.width) + "," + parseInt(object.parent.params.height) + ";" + room.params.wall_material + ";"  + 'Shaft_wall' + "_" + object.uuid;
                } else {
                    name = parseInt(object.parent.params.depth) + "," + parseInt(object.parent.params.height) + ";" + room.params.wall_material + ";" + 'Shaft_wall' + "_" + object.uuid;
                }

                gltfNode.name = name;

            }

            if (object.parent && object.parent.name === 'Cokol'){

                if(object.type === 'CabinetLeg'){

                } else {

                    if(object.parent.temp_type === 'CabinetCokol'){

                    } else{
                        gltfNode.name = parseInt(object.params.width*10) + "," + parseInt(object.params.height*10) + ";" + object.params.material + ';' + object.parent.name + '_' + object.uuid;
                        console.log(gltfNode.name);
                    }


                }
            }

            if(object.parent && object.parent.temp_type === 'CabinetCokol'){
                gltfNode.name = parseInt(object.params.width*10) + "," + parseInt(object.params.height*10) + ";" + object.params.material + ';' + 'Cokol' + '_' + object.uuid;
                console.log(object)
                console.log(gltfNode.name )
            }

            if(object.isMesh){

                function get_parent_recursive(obj) {
                    if(obj.parent.uuid === room.uuid){
                        return obj
                    } else {
                        return get_parent_recursive(obj.parent)
                    }
                }

                function get_parent_recursive_hinge(obj) {
                    if(obj.parent.name.indexOf('hinge') > -1 || obj.parent.type == 'Model'){
                        return obj.parent
                    } else {
                        return get_parent_recursive(obj.parent)
                    }
                }

                let par = get_parent_recursive(object)


                if(par.type == 'Model' && par.params.opens_support) {

                    var arr = par.params.model.split('/');

                    if(object.name.indexOf('door') >-1){
                        let h = get_parent_recursive_hinge(object)
                        console.log(h)
                        if(h.type != 'Model'){
                            if(object.name.indexOf('rtl') > -1)  h.name = 'Door_rtl_Self_' + h.uuid
                            if(object.name.indexOf('ltr') > -1)  h.name = 'Door_ltr_Self_' + h.uuid
                            if(object.name.indexOf('simple_top') > -1)  h.name = 'Door_simple_top_Self_' + h.uuid
                            if(object.name.indexOf('simple_bottom') > -1)  h.name = 'Door_simple_bottom_Self_' + h.uuid
                            if(object.name.indexOf('front_top') > -1)  h.name = 'Door_front_top_Self_' + h.uuid
                        }
                        console.log(h.name)
                    }

                    if(object.name.indexOf('locker') >-1){
                        let h = get_parent_recursive_hinge(object)
                        console.log(h)
                        if(h.type != 'Model') h.name = 'Locker_object_' + h.uuid
                        console.log(h.name)
                    }



                    if(object.name.indexOf('gen') > -1) {
                        if (arr[1] !== "common_assets") {
                            gltfNode.name = "Model_" + arr[1] + "_" + arr[2] + "_" + object.uuid;
                        } else {
                            gltfNode.name = "Model_" + arr[3] + "_" + object.parent.parent.params.id + "_" + object.uuid;
                        }
                    }

                    if(object.name.indexOf('facmat') > -1){
                        gltfNode.name = object.parent.parent.params.width + ','+ object.parent.parent.params.height +';'+ project_settings.selected_materials.top.facades +';Facade_' + object.parent.parent.cabinet_type;
                    }

                    if(object.name.indexOf('mirror')>-1){
                        gltfNode.name = 'Mirror_' +  object.uuid
                    }
                    if(object.name.indexOf('glass') !== -1 ){
                        gltfNode.name = 'Glass';
                    }


                } else {

                    if (object.parent && object.parent.name === 'Railing') {
                        gltfNode.name = object.parent.parent.params.width + "," + object.parent.parent.params.railing_height + ";" + project_settings.selected_materials.top.facades + ";Cornice_top";
                    }

                    if (object.parent && object.parent.name === 'Filling') {
                        gltfNode.name = object.parent.parent.params.filling_width + "," + object.parent.parent.params.filling_height + ";" + project_settings.selected_materials.top.facades + ";Cornice_top";
                    }

                    if (object.parent && object.parent.type === 'Cornice') {

                        if (object.parent.visible === false) {
                            return
                        }

                        let b = box3.setFromObject(object).getSize();


                        if (object.parent.rotation === 0 || object.parent.rotation === Math.PI) {
                            gltfNode.name = parseInt(b.x * units) + "," + parseInt(b.y * units) + ";" + project_settings.selected_materials.top.facades + ";Cornice_top";
                        } else {
                            gltfNode.name = parseInt(b.z * units) + "," + parseInt(b.y * units) + ";" + project_settings.selected_materials.top.facades + ";Cornice_top";
                        }

                    }

                    if(object.name.indexOf('glass') !== -1 ){
                        gltfNode.name = 'Glass';

                        if(object.parent.parent){
                            if( object.parent.parent.type == 'Facade'){

                                let fac = object.parent.parent;
                                if(fac.params.glass_material !== glass_mat){
                                    let glass_material = get_material_by_id(fac.params.glass_material, glass_materials_catalog);
                                    let b = box3.setFromObject(object).getSize();


                                    if(fac.parent.parent.parent.rotation.y === 0 || fac.parent.parent.parent.rotation.y === Math.PI) {

                                        gltfNode.name = parseInt(b.x * units) + "," + parseInt(b.y * units) + ";" + glass_material.id + ";" + "Glass_" + object.uuid;
                                    } else {
                                        gltfNode.name = parseInt(b.z * units) + "," + parseInt(b.y * units) + ";" + glass_material.id + ";" + "Glass_" + object.uuid;

                                    }
                                }

                            }
                        }

                    }

                    if(object.parent.parent){

                        if(object.parent.parent.type === 'Model'){

                            var arr = object.parent.parent.params.model.split('/');

                            if(arr[1] !== "common_assets"){
                                gltfNode.name = "Model_" + arr[1] + "_" + arr[2] + "_" + object.uuid;
                            } else {
                                gltfNode.name = "Model_" + arr[3] + "_" + object.parent.parent.params.id + "_" + object.uuid;
                            }

                            if(object.parent.parent.params.model.indexOf('bottle') > -1){
                                gltfNode.name = 'Bottle' + object.uuid;
                            }

                            if(object.parent.parent.params.model.indexOf('built_in_cookers') > -1){
                                gltfNode.name = 'Builtcook_' + object.uuid;
                                console.log(123231)
                            }


                            if(object.parent.parent.params.model.indexOf('cooking_panels') > -1){
                                gltfNode.name = 'Cookpanel_' + object.uuid;
                            }

                            if(object.parent.parent.params.model.indexOf('sinks') > -1){
                                gltfNode.name = 'Sink_' + object.uuid;
                            }



                            if(object.parent.parent.params.model.indexOf('window') > -1){
                                if (object.name.indexOf('window') > -1){
                                    gltfNode.name = 'wingl_' + object.uuid;
                                } else {
                                    gltfNode.name = 'Window_' + object.uuid;
                                }

                            }

                            if(object.parent.parent.params.model.indexOf('door') > -1){
                                gltfNode.name = 'Doormodel_' + object.uuid;
                            }

                            if(object.name.indexOf('facmat') > -1){

                                gltfNode.name = object.parent.parent.params.width + ','+ object.parent.parent.params.height +';'+ project_settings.selected_materials.top.facades +';Facade_' + object.parent.parent.cabinet_type;
                            }

                            if(object.name.indexOf('mirror')>-1){
                                gltfNode.name = 'Mirror_' +  object.uuid
                            }

                        }

                        if(object.parent.parent.type == 'Bardesk'){
                            gltfNode.name = 'Sink_' + object.uuid;
                        }

                        if(object.parent.parent.name == 'Tabletop'){
                            gltfNode.name = parseInt(object.parent.params.width) + "," + parseInt(object.parent.params.height) + ";" + object.parent.params.material +  ";" + 'Tabletop_mesh';
                        }

                        if(object.parent.parent.type == 'Washer' || object.parent.parent.type == 'Simple_model'){
                            if(object.parent.parent.params.model.indexOf('built_in_cookers')< 0){
                                if(object.name.indexOf('cut') > -1 || object.name.indexOf('smes') > -1) return null;

                                if(object.name.indexOf('metall') > -1){
                                    gltfNode.name = 'Sink_' + object.uuid;
                                } else {

                                    if(object.parent.parent.type == 'Simple_model'){
                                        try{
                                            gltfNode.name = object.parent.parent.parent.parent.params.material.id + ';' + 'Washer_' + object.uuid;
                                        } catch (e) {
                                            gltfNode.name = 'Bottle' + object.uuid;
                                        }
                                    } else {
                                        gltfNode.name = object.parent.parent.params.material.id + ';' + 'Washer_' + object.uuid;
                                    }


                                }
                            } else {
                                if(object.parent.parent.params.model.indexOf('model.fbx') > -1){
                                    gltfNode.name = 'BuiltcookWhite_' + object.uuid;
                                } else {
                                    gltfNode.name = 'Microwave1_' + object.uuid;
                                }
                            }





                        }
                    }


                    if (object.parent && object.parent.name === 'Cabinet'){
                        if(object.params.width){
                            gltfNode.name = parseInt(object.params.width*10) + "," + parseInt(object.params.height*10) + ";" + object.parent.parent.params.cabinet.material + ";" + object.parent.name + '_' + object.parent.parent.params.cabinet_group + "_" + object.uuid;
                        } else {
                            gltfNode.name = "100,100;" + object.parent.parent.params.cabinet.material + ";" + object.parent.name + '_' + object.parent.parent.params.cabinet_group + "_" + object.uuid;
                        }
                    }

                    if (object.parent && object.parent.name === 'Shelves'){
                        if(object.params.width){
                            gltfNode.name = parseInt(object.params.width*10) + "," + parseInt(object.params.height*10) + ";" + object.parent.parent.params.cabinet.material + ";" + object.parent.name + '_' + object.parent.parent.params.cabinet_group + "_" + object.uuid;
                        } else {
                            gltfNode.name = "100,100;" + object.parent.parent.params.cabinet.material + ";" + object.parent.name + '_' + object.parent.parent.params.cabinet_group + "_" + object.uuid;
                        }
                    }

                    if(object.name.indexOf('mirror') !== -1){
                        gltfNode.name = 'Mirror';
                    }

                    if (object.parent && object.parent.name === 'Shelve'){
                        gltfNode.name = parseInt(object.parent.parent.params.width) + "," + parseInt(object.parent.parent.params.height) + ";" + object.parent.parent.params.material +";"+"Cabinet_top_" + object.uuid;
                    }

                    if (object.parent && object.parent.name === 'wall_panel'){
                        gltfNode.name = parseInt(object.parent.params.width*10) + "," + parseInt(object.parent.params.height*10) + ";" + project_settings.selected_materials.wall_panel + ";" + object.parent.name + '_mesh_' + object.uuid;
                    }

                    if (object.name === 'wall_panel'){
                        let par = get_parent_by_type(object, 'type', 'Wall_panel')
                        gltfNode.name = parseInt(object.params.width*10) + "," + parseInt(object.params.height*10) + ";" + par.params.material.id + ";" + 'wall_panel' + '_mesh_' + object.uuid;
                    }




                    if (object.parent && object.parent.name === 'Tabletop'){
                        gltfNode.name = parseInt(object.parent.params.width) + "," + parseInt(object.parent.params.depth) + ";" + object.parent.params.material +  ";" + 'Tabletop_mesh';
                    }



                    if (
                        object.parent && object.parent.name === 'Facade_model' && object.name != 'furni' && object.name.indexOf('glass') == -1 ||
                        object.parent && object.parent.name === 'Facade' && object.name != 'furni' && object.name.indexOf('glass') == -1
                    ){


                        console.log(11111)
                        console.log(object.name)
                        console.log(object.parent)
                        console.log(object.parent.parent)

                        if(object.parent.parent && object.parent.parent.parent && object.parent.parent.parent.name == 'Tabletop'){
                            gltfNode.name = parseInt(object.parent.parent.params.width) + "," + parseInt(object.parent.parent.params.height) + ";" + object.parent.parent.params.material +  ";" + 'Tabletop_mesh_' + object.uuid;
                        } else {
                            if(project_settings.fixed_materials === undefined || project_settings.fixed_materials === null || project_settings.fixed_materials === ''){
                                project_settings.fixed_materials = [];
                            }
                            let mats = project_settings.fixed_materials;

                            let flag = 0;

                            for (let i = 0; i < mats.length; i++) {
                                let str = (i + 1);


                                if (object.name.indexOf('mat' + str) > -1) {
                                    gltfNode.name = parseInt(object.parent.parent.params.width) + "," + parseInt(object.parent.parent.params.height) + ";" + mats[i] +";"+"Facade_" + object.parent.parent.params.group + "_mesh_" + object.uuid;
                                    flag = 1;
                                }
                            }

                            if(object.name.indexOf('v(2)') > -1){
                                let custom_material_set = object.name.match(/m\((.*?)\)/);
                                if(custom_material_set != null){
                                    let mat_key = custom_material_set[1];

                                    if(mat_key != 'corpmat'){
                                        try {
                                            if(project_settings.selected_materials.facades[project_settings.defaults.facades[object.parent.parent.params.group]][mat_key][object.parent.parent.params.group]){
                                                let mat_id = project_settings.selected_materials.facades[project_settings.defaults.facades[object.parent.parent.params.group]][mat_key][object.parent.parent.params.group];
                                                gltfNode.name = parseInt(object.parent.parent.params.width) + "," + parseInt(object.parent.parent.params.height) + ";" + mat_id +";"+"Facade_" + object.parent.parent.params.group + "_mesh_" + object.uuid;
                                                flag = 1;
                                            }
                                        } catch (e) {
                                            mat_key = 'gen'
                                            if(project_settings.selected_materials.facades[project_settings.defaults.facades[object.parent.parent.params.group]][mat_key][object.parent.parent.params.group]){
                                                let mat_id = project_settings.selected_materials.facades[project_settings.defaults.facades[object.parent.parent.params.group]][mat_key][object.parent.parent.params.group];
                                                gltfNode.name = parseInt(object.parent.parent.params.width) + "," + parseInt(object.parent.parent.params.height) + ";" + mat_id +";"+"Facade_" + object.parent.parent.params.group + "_mesh_" + object.uuid;
                                                flag = 1;
                                            }
                                        }

                                    } else {
                                        let mat_id = project_settings.selected_materials[object.parent.parent.params.group].corpus
                                        gltfNode.name = parseInt(object.parent.parent.params.width) + "," + parseInt(object.parent.parent.params.height) + ";" + mat_id +";"+"Facade_" + object.parent.parent.params.group + "_mesh_" + object.uuid;
                                        flag = 1;
                                    }



                                }
                            }

                            if(flag == 0){
                                if( object.name.indexOf('pat') > -1){
                                    let b = box3.setFromObject(object).getSize();

                                    if(object.parent.parent.parent.parent.parent.rotation.y === 0 || object.parent.parent.parent.parent.parent.rotation.y === Math.PI){
                                        gltfNode.name = parseInt(b.x * units) + "," + parseInt(b.y * units) +";" + project_settings.selected_materials.pat + ";"+"Pat_" + object.parent.parent.params.group + "_" + object.uuid;
                                    } else {
                                        gltfNode.name = parseInt(b.z * units) + "," + parseInt(b.y * units) +";" + project_settings.selected_materials.pat + ";"+"Pat_" + object.parent.parent.params.group + "_" + object.uuid;
                                    }


                                } else if(object.name.indexOf('botfacmat') > -1){

                                    let b = box3.setFromObject(object).getSize();

                                    if(object.parent.parent.parent.parent.parent.rotation.y === 0 || object.parent.parent.parent.parent.parent.rotation.y === Math.PI){
                                        gltfNode.name = parseInt(b.x * units) + "," + parseInt(b.y * units) +";" + project_settings.selected_materials.bottom.facades + ";"+"Facade_bottom" + "_mesh_" + object.uuid;
                                    } else {
                                        gltfNode.name = parseInt(b.z * units) + "," + parseInt(b.y * units) +";" + project_settings.selected_materials.bottom.facades + ";"+"Facade_bottom" + "_mesh_" + object.uuid;
                                    }


                                } else {
                                    gltfNode.name = parseInt(object.parent.parent.params.width) + "," + parseInt(object.parent.parent.params.height) + ";" + object.parent.parent.params.material +";"+"Facade_" + object.parent.parent.params.group + "_mesh_" + object.uuid;

                                }
                            }
                        }
                        console.log(gltfNode.name)
                        if(object.parent.parent && object.parent.parent.name == 'LOCKER_BLUM'){
                            gltfNode.name = 'Bottle' + object.uuid;
                        }




                    }


                }



            }






            if(object.type == 'Cabinet'){
                gltfNode.name = 'Cabinet_parent_' + object.uuid;
                console.log(gltfNode.name)
            }

            if(object.type == 'Door'){
                gltfNode.name = 'Door_' + object.params.type + '_' + object.uuid;
            }

            if(object.isMesh && object.parent.type === 'Handle'){
                gltfNode.name = object.parent.params.id + ';' + 'handle' + ';' + object.material.color.getHexString() + ';' + '_' + object.uuid;
            }



            serializeUserData( object, gltfNode );

            if ( object.isMesh || object.isLine || object.isPoints ) {


                if(object.parent.type === 'Sizes_obj') return null;
                if(object.size_box ) return null;

                var mesh = processMesh( object );

                if ( mesh !== null ) {

                    gltfNode.mesh = mesh;

                }

            } else if ( object.isCamera ) {


                gltfNode.camera = processCamera( object );

            } else if ( object.isDirectionalLight || object.isPointLight || object.isSpotLight ) {

                return null

                if ( ! extensionsUsed[ 'KHR_lights_punctual' ] ) {

                    outputJSON.extensions = outputJSON.extensions || {};
                    outputJSON.extensions[ 'KHR_lights_punctual' ] = { lights: [] };
                    extensionsUsed[ 'KHR_lights_punctual' ] = true;

                }

                gltfNode.extensions = gltfNode.extensions || {};
                gltfNode.extensions[ 'KHR_lights_punctual' ] = { light: processLight( object ) };

            } else if ( object.isLight ) {
                return null

                console.warn( 'THREE.GLTFExporter: Only directional, point, and spot lights are supported.', object );
                return null;

            }

            if ( object.isSkinnedMesh ) {

                skins.push( object );

            }

            if ( object.children.length > 0 ) {

                var children = [];

                for ( var i = 0, l = object.children.length; i < l; i ++ ) {

                    var child = object.children[ i ];

                    if ( child.visible || options.onlyVisible === false ) {

                        var node = processNode( child );

                        if ( node !== null ) {

                            children.push( node );

                        }

                    }

                }

                if ( children.length > 0 ) {

                    gltfNode.children = children;

                }


            }

            outputJSON.nodes.push( gltfNode );

            var nodeIndex = outputJSON.nodes.length - 1;
            nodeMap.set( object, nodeIndex );


            return nodeIndex;

        }

        /**
         * Process Scene
         * @param  {THREE.Scene} node Scene to process
         */
        function processScene( scene ) {

            if ( ! outputJSON.scenes ) {

                outputJSON.scenes = [];
                outputJSON.scene = 0;

            }

            var gltfScene = {

                nodes: []

            };

            if ( scene.name !== '' ) {

                gltfScene.name = scene.name;

            }

            if ( scene.userData && Object.keys( scene.userData ).length > 0 ) {

                gltfScene.extras = serializeUserData( scene );

            }

            outputJSON.scenes.push( gltfScene );

            var nodes = [];

            for ( var i = 0, l = scene.children.length; i < l; i ++ ) {

                var child = scene.children[ i ];

                if ( child.visible || options.onlyVisible === false ) {


                    var node = processNode( child );



                    if ( node !== null ) {

                        nodes.push( node );

                    }

                }

            }

            if ( nodes.length > 0 ) {

                gltfScene.nodes = nodes;

            }

            serializeUserData( scene, gltfScene );

        }

        /**
         * Creates a THREE.Scene to hold a list of objects and parse it
         * @param  {Array} objects List of objects to process
         */
        function processObjects( objects ) {

            var scene = new THREE.Scene();
            scene.name = 'AuxScene';

            for ( var i = 0; i < objects.length; i ++ ) {

                // We push directly to children instead of calling `add` to prevent
                // modify the .parent and break its original scene and hierarchy
                scene.children.push( objects[ i ] );

            }

            processScene( scene );

        }

        function processInput( input ) {

            input = input instanceof Array ? input : [ input ];

            var objectsWithoutScene = [];

            for ( var i = 0; i < input.length; i ++ ) {

                if ( input[ i ] instanceof THREE.Scene ) {

                    processScene( input[ i ] );

                } else {

                    objectsWithoutScene.push( input[ i ] );

                }

            }

            if ( objectsWithoutScene.length > 0 ) {

                processObjects( objectsWithoutScene );

            }

            for ( var i = 0; i < skins.length; ++ i ) {

                processSkin( skins[ i ] );

            }

            for ( var i = 0; i < options.animations.length; ++ i ) {

                processAnimation( options.animations[ i ], input[ 0 ] );

            }

        }

        processInput( input );

        Promise.all( pending ).then( function () {

            // Merge buffers.
            var blob = new Blob( buffers, { type: 'application/octet-stream' } );

            // Declare extensions.
            var extensionsUsedList = Object.keys( extensionsUsed );
            if ( extensionsUsedList.length > 0 ) outputJSON.extensionsUsed = extensionsUsedList;

            if ( outputJSON.buffers && outputJSON.buffers.length > 0 ) {

                // Update bytelength of the single buffer.
                outputJSON.buffers[ 0 ].byteLength = blob.size;

                var reader = new window.FileReader();

                if ( options.binary === true ) {

                    // https://github.com/KhronosGroup/glTF/blob/master/specification/2.0/README.md#glb-file-format-specification

                    var GLB_HEADER_BYTES = 12;
                    var GLB_HEADER_MAGIC = 0x46546C67;
                    var GLB_VERSION = 2;

                    var GLB_CHUNK_PREFIX_BYTES = 8;
                    var GLB_CHUNK_TYPE_JSON = 0x4E4F534A;
                    var GLB_CHUNK_TYPE_BIN = 0x004E4942;

                    reader.readAsArrayBuffer( blob );
                    reader.onloadend = function () {

                        // Binary chunk.
                        var binaryChunk = getPaddedArrayBuffer( reader.result );
                        var binaryChunkPrefix = new DataView( new ArrayBuffer( GLB_CHUNK_PREFIX_BYTES ) );
                        binaryChunkPrefix.setUint32( 0, binaryChunk.byteLength, true );
                        binaryChunkPrefix.setUint32( 4, GLB_CHUNK_TYPE_BIN, true );

                        // JSON chunk.
                        var jsonChunk = getPaddedArrayBuffer( stringToArrayBuffer( JSON.stringify( outputJSON ) ), 0x20 );
                        var jsonChunkPrefix = new DataView( new ArrayBuffer( GLB_CHUNK_PREFIX_BYTES ) );
                        jsonChunkPrefix.setUint32( 0, jsonChunk.byteLength, true );
                        jsonChunkPrefix.setUint32( 4, GLB_CHUNK_TYPE_JSON, true );

                        // GLB header.
                        var header = new ArrayBuffer( GLB_HEADER_BYTES );
                        var headerView = new DataView( header );
                        headerView.setUint32( 0, GLB_HEADER_MAGIC, true );
                        headerView.setUint32( 4, GLB_VERSION, true );
                        var totalByteLength = GLB_HEADER_BYTES
                            + jsonChunkPrefix.byteLength + jsonChunk.byteLength
                            + binaryChunkPrefix.byteLength + binaryChunk.byteLength;
                        headerView.setUint32( 8, totalByteLength, true );

                        var glbBlob = new Blob( [
                            header,
                            jsonChunkPrefix,
                            jsonChunk,
                            binaryChunkPrefix,
                            binaryChunk
                        ], { type: 'application/octet-stream' } );

                        var glbReader = new window.FileReader();
                        glbReader.readAsArrayBuffer( glbBlob );
                        glbReader.onloadend = function () {

                            onDone( glbReader.result );

                        };

                    };

                } else {

                    reader.readAsDataURL( blob );
                    reader.onloadend = function () {

                        var base64data = reader.result;
                        outputJSON.buffers[ 0 ].uri = base64data;
                        onDone( outputJSON );

                    };

                }

            } else {

                onDone( outputJSON );

            }

        } );

    }

};

function get_parent_by_type(obj, key, val) {
    if (obj.parent[key] == val) {
        return obj.parent
    } else {
        return get_parent_by_type(obj.parent)
    }
}

THREE.GLTFExporter.Utils = {

    insertKeyframe: function ( track, time ) {

        var tolerance = 0.001; // 1ms
        var valueSize = track.getValueSize();

        var times = new track.TimeBufferType( track.times.length + 1 );
        var values = new track.ValueBufferType( track.values.length + valueSize );
        var interpolant = track.createInterpolant( new track.ValueBufferType( valueSize ) );

        var index;

        if ( track.times.length === 0 ) {

            times[ 0 ] = time;

            for ( var i = 0; i < valueSize; i ++ ) {

                values[ i ] = 0;

            }

            index = 0;

        } else if ( time < track.times[ 0 ] ) {

            if ( Math.abs( track.times[ 0 ] - time ) < tolerance ) return 0;

            times[ 0 ] = time;
            times.set( track.times, 1 );

            values.set( interpolant.evaluate( time ), 0 );
            values.set( track.values, valueSize );

            index = 0;

        } else if ( time > track.times[ track.times.length - 1 ] ) {

            if ( Math.abs( track.times[ track.times.length - 1 ] - time ) < tolerance ) {

                return track.times.length - 1;

            }

            times[ times.length - 1 ] = time;
            times.set( track.times, 0 );

            values.set( track.values, 0 );
            values.set( interpolant.evaluate( time ), track.values.length );

            index = times.length - 1;

        } else {

            for ( var i = 0; i < track.times.length; i ++ ) {

                if ( Math.abs( track.times[ i ] - time ) < tolerance ) return i;

                if ( track.times[ i ] < time && track.times[ i + 1 ] > time ) {

                    times.set( track.times.slice( 0, i + 1 ), 0 );
                    times[ i + 1 ] = time;
                    times.set( track.times.slice( i + 1 ), i + 2 );

                    values.set( track.values.slice( 0, ( i + 1 ) * valueSize ), 0 );
                    values.set( interpolant.evaluate( time ), ( i + 1 ) * valueSize );
                    values.set( track.values.slice( ( i + 1 ) * valueSize ), ( i + 2 ) * valueSize );

                    index = i + 1;

                    break;

                }

            }

        }

        track.times = times;
        track.values = values;

        return index;

    },

    mergeMorphTargetTracks: function ( clip, root ) {

        var tracks = [];
        var mergedTracks = {};
        var sourceTracks = clip.tracks;

        for ( var i = 0; i < sourceTracks.length; ++ i ) {

            var sourceTrack = sourceTracks[ i ];
            var sourceTrackBinding = THREE.PropertyBinding.parseTrackName( sourceTrack.name );
            var sourceTrackNode = THREE.PropertyBinding.findNode( root, sourceTrackBinding.nodeName );

            if ( sourceTrackBinding.propertyName !== 'morphTargetInfluences' || sourceTrackBinding.propertyIndex === undefined ) {

                // Tracks that don't affect morph targets, or that affect all morph targets together, can be left as-is.
                tracks.push( sourceTrack );
                continue;

            }

            if ( sourceTrack.createInterpolant !== sourceTrack.InterpolantFactoryMethodDiscrete
                && sourceTrack.createInterpolant !== sourceTrack.InterpolantFactoryMethodLinear ) {

                if ( sourceTrack.createInterpolant.isInterpolantFactoryMethodGLTFCubicSpline ) {

                    // This should never happen, because glTF morph target animations
                    // affect all targets already.
                    throw new Error( 'THREE.GLTFExporter: Cannot merge tracks with glTF CUBICSPLINE interpolation.' );

                }

                console.warn( 'THREE.GLTFExporter: Morph target interpolation mode not yet supported. Using LINEAR instead.' );

                sourceTrack = sourceTrack.clone();
                sourceTrack.setInterpolation( THREE.InterpolateLinear );

            }

            var targetCount = sourceTrackNode.morphTargetInfluences.length;
            var targetIndex = sourceTrackNode.morphTargetDictionary[ sourceTrackBinding.propertyIndex ];

            if ( targetIndex === undefined ) {

                throw new Error( 'THREE.GLTFExporter: Morph target name not found: ' + sourceTrackBinding.propertyIndex );

            }

            var mergedTrack;

            // If this is the first time we've seen this object, create a new
            // track to store merged keyframe data for each morph target.
            if ( mergedTracks[ sourceTrackNode.uuid ] === undefined ) {

                mergedTrack = sourceTrack.clone();

                var values = new mergedTrack.ValueBufferType( targetCount * mergedTrack.times.length );

                for ( var j = 0; j < mergedTrack.times.length; j ++ ) {

                    values[ j * targetCount + targetIndex ] = mergedTrack.values[ j ];

                }

                mergedTrack.name = '.morphTargetInfluences';
                mergedTrack.values = values;

                mergedTracks[ sourceTrackNode.uuid ] = mergedTrack;
                tracks.push( mergedTrack );

                continue;

            }

            var mergedKeyframeIndex = 0;
            var sourceKeyframeIndex = 0;
            var sourceInterpolant = sourceTrack.createInterpolant( new sourceTrack.ValueBufferType( 1 ) );

            mergedTrack = mergedTracks[ sourceTrackNode.uuid ];

            // For every existing keyframe of the merged track, write a (possibly
            // interpolated) value from the source track.
            for ( var j = 0; j < mergedTrack.times.length; j ++ ) {

                mergedTrack.values[ j * targetCount + targetIndex ] = sourceInterpolant.evaluate( mergedTrack.times[ j ] );

            }

            // For every existing keyframe of the source track, write a (possibly
            // new) keyframe to the merged track. Values from the previous loop may
            // be written again, but keyframes are de-duplicated.
            for ( var j = 0; j < sourceTrack.times.length; j ++ ) {

                var keyframeIndex = this.insertKeyframe( mergedTrack, sourceTrack.times[ j ] );
                mergedTrack.values[ keyframeIndex * targetCount + targetIndex ] = sourceTrack.values[ j ];

            }

        }

        clip.tracks = tracks;

        return clip;

    }

};