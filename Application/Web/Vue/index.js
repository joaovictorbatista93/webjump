var vm = new Vue({
		el:"#app",
		data:{
			add: false,
			edit:false,
			remove:false,
			showAddCategoria: false,
            showAddProduto: false,
			habilitarCep: true,
            produtos_exists: false,
            categoria_exists: false,
            produto:{categoria:{}},
            produtos:{},
            categoria:{},
            categorias:[],
            produtoCategoria:{},
            testeCategoria:[],
            categoriasSalvas:[]
		},
		mounted: function() {
			this.get().then(function (result) {});
			this.getCategoria();
		},
		methods:{
            addNewCategory() {
                let p = new Promise((resolve, reject) => {
                    window.location.href="#endForm";
                    var formData = vm.toFormData(vm.categoria);
                    axios.post("Application/Process/CategoriaProcess.php?action=addNew", formData).then(function(response){
                        vm.produto = [];
                        vm.get();
                        vm.edit = false;
                        vm.remove = false;
                        document.location.reload(true);
                    });
                });

                return p;
            },
			addNewProduct() {
				let p = new Promise((resolve, reject) => {
					window.location.href="#endForm";
					var formData = vm.toFormData(vm.produto);
					var appFile = vm.$refs.file.files[0];
					formData.append("file", appFile);
					axios.post("Application/Process/ProdutoProcess.php?action=addNew", formData).then(function(response){
						vm.produto = [];
						vm.get();
						vm.edit = false;
						vm.remove = false;
						document.location.reload(true);
					});
               	});

				return p;
			},
			loadDataForUpdate(value, produtoCategoria = null) {
				let p = new Promise((resolve, reject) => {
				    if(!vm.showAddProduto) {
                        vm.showAddProduto = true;
                        vm.add = false;
                        vm.edit = true;
                        vm.produto.id = value.id;
                        vm.produto.nome = value.nome;
                        vm.produto.sku = value.sku;
                        vm.produto.preco = value.preco;
                        vm.produto.descricao = value.descricao;
                        vm.produto.quantidade = value.quantidade;
                        vm.produto.categoria = vm.produtoCategoria;

                        var cat = {};
                        var listCategoria = [];
                        var listIdsCategoria = [];

                        vm.categorias.forEach(function (value, key) {
                            vm.produtoCategoria.forEach(function (v, k) {
                                if (v.categoria == value.id) {
                                    cat = {
                                        "id": value.id,
                                        "nome": value.nome,
                                        "produto": v.produto,
                                        "salvo": value.id
                                    }
                                    listIdsCategoria.push(value.id);
                                    listCategoria.push(cat);
                                }
                            })
                            listCategoria.forEach(function (i, j) {
                                var hasId = false;

                                listIdsCategoria.forEach(function(id, key) {
                                    if (id == value.id) {
                                        hasId = true;
                                    }
                                    else {
                                        hasId = false;
                                    }
                                })
                                if (value.id != i.id && hasId == false) {
                                    cat = {
                                        "id": value.id,
                                        "nome": value.nome,
                                        "salvo": ""
                                    }
                                    listIdsCategoria.push(value.id);
                                    listCategoria.push(cat);
                                }
                            })
                        });
                        vm.categoriasSalvas = listCategoria;
				    }
				    else {
				        vm.showAddProduto = false;
                        vm.add = false;
                        vm.edit = false;
                        vm.remove = false;
                    }
               	});

				return p;
			},
			update() {

				let p = new Promise((resolve, reject) => {
                    var categorias = '';
				    vm.categoriasSalvas.forEach(function(cat, key) {
				        if (cat.salvo) {
				            categorias += cat.id += ", ";
                        }
                    })
                    vm.produto.categoria = categorias;
					var formData = vm.toFormData(vm.produto);
					axios.post("Application/Process/ProdutoProcess.php?action=update", formData).then(function(response){
						vm.product = [];
						vm.showAdd = false;
						vm.remove = false;
						vm.add = false;
						vm.get();
						document.location.reload(true);
					});
               	});

				return p;
			},
			doRemove(value) {

				let p = new Promise((resolve, reject) => {
					vm.produto.id = value.id;
					var formData = vm.toFormData(vm.produto);

					axios.post("Application/Process/ProdutoProcess.php?action=delete", formData).then(function(response){
						vm.get();
						vm.showAdd = false;
						vm.edit = false;
						vm.add = false;
						document.location.reload(true);
					});
               	});

				return p;
			},
			get() {
				let p = new Promise((resolve, reject) => {
					axios.get("Application/Process/ProdutoProcess.php?action=get").then(function(response) {
						if(!response.data.error) {
							if (response.data.produto) {
							    vm.produtos = response.data.produto;
                                vm.produtos.forEach(function (value, key) {
                                    vm.getCategoriaByProduct(value.id).then(function (result) {
                                        value.categoria = vm.produtoCategoria;
                                    });
                                })
							}
							else {
								vm.produto_exists = false;
							}
						}
					});
               	});
				return p;
			},
            getCategoria() {
                let p = new Promise((resolve, reject) => {
                    axios.get("Application/Process/CategoriaProcess.php?action=get").then(function(response) {
                        if(!response.data.error) {
                            if (response.data.categoria) {
                                    vm.categorias = response.data.categoria;
                                    vm.categoria_exists = true;
                            }
                            else {
                                vm.categoria_exists = false;
                            }
                        }
                    });
                });
            },
            getCategoriaByProduct(product) {
                let p = new Promise((resolve, reject) => {
                    var formData = new FormData();
                    formData.append('produto', product);
                    axios.post("Application/Process/ProdutoCategoriaProcess.php?action=getCategoriaByProduct", formData).then(function(response) {
                        if(!response.data.error) {
                            if (response.data.produto_categoria) {
                                vm.produtoCategoria = response.data.produto_categoria;
                                vm.categoria_exists = true;
                                resolve(response.data.produto_categoria);
                            }
                            else {
                                vm.categoria_exists = false;
                            }
                        }
                    })
                });
                return p;
            },
			checkForm(e) {
	          e.preventDefault();
	        },
			toFormData(obj){
				var fd = new FormData();
				for(var i in obj){
					fd.append(i,obj[i]);
				}
				return fd;
			}
		}
	})
