<script>
        require([
                "dojo/store/JsonRest",
                "dijit/Tree",
                "dijit/tree/ObjectStoreModel",
                "dojo/domReady!"
        ], function(JsonRest, Tree, ObjectStoreModel){

                // create store
                var usGov = new JsonRest({
                        target: "data/",

                        getChildren: function(object){
                                // object may just be stub object, so get the full object first and then return it's
                                // list of children
                                return this.get(object.id).then(function(fullObject){
                                        return fullObject.children;
                                });
                        }
                });

                // create model to interface Tree to store
                var model = new ObjectStoreModel({
                        store: usGov,

                        getRoot: function(onItem){
                                this.store.get("root").then(onItem);
                        },

                        mayHaveChildren: function(object){
                                return "children" in object;
                        }
                       ,getLabel: function(object){
                           return "object.id";
                       }

                });

                var tree = new Tree({
                        model: model,
                        persist: false
                }, "tree"); // make sure you have a target HTML element with this id
                tree.startup();
        });
</script>
