<!DOCTYPE html>
	<html>
		<head>
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
			<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
			<script src="https://unpkg.com/vue"></script>
			<script src="https://kit.fontawesome.com/a1c4d33208.js"></script>

			<!-- Bootstrap CSS -->
			<script src="https://kit.fontawesome.com/a1c4d33208.js"></script>
			<title>Cadastro de Produto</title>
		</head>
		<body>
			<div id="app" class="container mt-4">
				<form class=" mt-4" action="" method="POST" @submit="checkForm">
					<div class="form-group">
						<div class="busca">
							<legend> Lista de Produtos</legend>
						</div>
					</div>
					<div class="show" >
						<div class="row mb-4"  style="position: relative">
							<div class="col-md-12">
								<div class="table-responsive-sm mt-2">
									<table class="table table-hover">
									  <thead>
									    <tr>
									      <th class="text-center" scope="col">id</th>
									      <th class="text-center" scope="col">nome</th>
									      <th class="text-center" scope="col">sku</th>
									      <th class="text-center" scope="col">preço</th>
									      <th class="text-center" scope="col">descricao</th>
									      <th class="text-center" scope="col">quantidade</th>
									    </tr>
									  </thead>
									  <tbody>
									  	<template v-if="produtos.length > 1">
                                            <tr v-for="value in produtos">
                                              <td  class="text-center">{{value.id}}</td>
                                              <td  class="text-center">{{value.nome}}</td>
                                              <td  class="text-center">{{value.sku}}</td>
                                              <td  class="text-center">{{value.preco}}</td>
                                              <td  class="text-center">{{value.descricao}}</td>
                                              <td  class="text-center">{{value.quantidade}}</td>
                                              <td  class="text-center"><button class="btn-sm btn-warning" @click="getCategoriaByProduct(value.id); loadDataForUpdate(value)">Editar</button></td>
                                              <td  class="text-center"><button class="btn-sm btn-danger" @click="doRemove(value)">Remover</button></td>
                                            </tr>
									     </template>
                                        <template v-else-if="produtos!=false">
                                            <tr>
                                                <td  class="text-center">{{produtos["id"]}}</td>
                                                <td  class="text-center">{{produtos["nome"]}}</td>
                                                <td  class="text-center">{{produtos["sku"]}}</td>
                                                <td  class="text-center">{{produtos["preco"]}}</td>
                                                <td  class="text-center">{{produtos["descricao"]}}</td>
                                                <td  class="text-center">{{produtos["quantidade"]}}</td>
                                                <td  class="text-center"><button class="btn-sm btn-warning" @click="getCategoriaByProduct(produtos['id']); loadDataForUpdate(produtos, produtoCategoria)">Editar</button></td>
                                                <td  class="text-center"><button class="btn-sm btn-danger" @click="doRemove(produtos)">Remover</button></td>
                                            </tr>
                                        </template>
									  </tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="add" v-if="showAddCategoria">
                        <div class="form-group">
                            <label><h4>NOME</h4></label>
                            <input class="form-control" type="text" name="nome" v-model="categoria.nome" ></input>
                        </div >
                        <div class="actions container">
                            <div class="row">
                                <div v-if="add" class="add col-md-12">
                                    <button class="btn btn-lg btn-primary btn-block mt-2" type="submit" name="env" @click="addNewCategory()"> CONFIRMAR</button>
                                </div>
                            </div>
                        </div>
					</div>
                    <div class="add" v-if="showAddProduto">
                        <div class="form-group">
                            <label><h4>NOME</h4></label>
                            <input class="form-control" type="text" name="nome" v-model="produto.nome" ></input>
                        </div >
                        <div class="form-group">
                            <label><h4>SKU</h4></label>
                            <input class="form-control" type="text" name="sku" v-model="produto.sku" ></input>
                        </div>
                        <div class="form-group">
                            <label><h4>PREÇO</h4></label>
                            <input class="form-control" type="text" name="preco" v-model="produto.preco" ></input>
                        </div >
                        <div class="form-group">
                            <label><h4>DESCRIÇÃO</h4></label>
                            <input class="form-control" type="text" name="descricao" v-model="produto.descricao" ></input>
                        </div>
                        <div class="form-group">
                            <label><h4>QUANTIDADE</h4></label>
                            <input class="form-control" type="text" name="quantidade" v-model="produto.quantidade"></input>
                        </div>
                        <div class="form-group">
                            <div v-if="produtos">
                                <div v-if="add">
                                    <label><h4>CATEGORIA</h4></label>
                                    <div class="form-group" v-for="(value) in categorias">
                                        <input class=""  type="checkbox" :id="value.id" :value="value.id" v-model="produto.categoria"/><p>{{value.nome}}</p>
                                    </div>
                                </div>
                                <div v-else-if="edit">
                                    <label><h4>CATEGORIA</h4></label>
                                    <div class="form-group" v-for="(v) in categorias">
                                        <div v-for = "cs in categoriasSalvas">
                                           <div v-if = "cs.id == v.id">
                                                <input class=""  type="checkbox" :id="v.id" :value="v.id"  v-model="cs.salvo"/><p>{{v.nome}}</p>
                                           </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="conteudo">Enviar imagem:</label>
                            <input type="file" ref="file" name="pic" accept="image/*">
                        </div>
                        <div class="actions container">
                            <div class="row">
                                <div v-if="add" class="add col-md-12">
                                    <button class="btn btn-lg btn-primary btn-block mt-2" type="submit" name="env" @click="addNewProduct()"> CONFIRMAR</button>
                                </div>
                                <div v-if="edit" class="add col-md-12">
                                    <button class="btn btn-lg btn-primary btn-block mt-2" type="submit" name="env" @click="update(categoriasSalvas)"> CONFIRMAR</button>
                                </div>
                                <div v-if="remove" class="add col-md-12">
                                    <button class="btn btn-lg btn-primary btn-block mt-2" type="submit" name="env" @click="remove()"> CONFIRMAR</button>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="actions container">
						<div class="row">
							<div class="add col-md-6">
								<button class="btn btn-lg btn-success btn-block mt-2" name="env" @click="showAddCategoria=true; showAddProduto=false; categoria={}; produto={categoria:[]}; add=true"> Adicionar Categoria</button>
							</div>
                            <div class="add col-md-6">
                                <button class="btn btn-lg btn-success btn-block mt-2" name="env" @click="showAddProduto=true; edit = false; showAddCategoria=false; produto={categoria:[]}; add=true"> Adicionar Produto</button>
                            </div>
						</div>
					</div>
					<div id="endForm" class="mt-4" hidden>
				</form>
			</div>
		</body>
	</html>

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="Application/Web/Vue/index.js" />
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
