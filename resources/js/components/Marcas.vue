<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- início do card de busca que é um componente Vue -->
                <card-component titulo="Busca de marcas">
                    <!-- Template que contem seu conteudo -->
                    <template v-slot:conteudo>
                        <div class="form-row">
                            <div class="col mb-3">
                                <input-container-component titulo="ID" id="inputId" id-help="idHelp" texto-ajuda="Opcional. Informe o ID da marca">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID" v-model="busca.id">
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component titulo="Nome da marca" id="inputNome" id-help="nomeHelp" texto-ajuda="Opcional. Informe o nome da marca">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp" placeholder="Nome da marca" v-model="busca.nome">
                                </input-container-component>
                            </div>
                        </div>
                    </template>
                    <!-- Template que contem o rodape -->
                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right" @click="pesquisar()">Pesquisar</button>
                    </template>
                </card-component>
                <!-- fim do card de busca -->


                <!-- início do card de listagem de marcas -->
                <card-component titulo="Relação de marcas">
                    <!-- Template que contem a table -->
                    <template v-slot:conteudo>
                        <table-component
                            :dados="marcas.data"
                            :visualizar="{visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaVisualizar'}"
                            :atualizar="{visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaAtualizar'}"
                            :remover="{visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaRemover'}"
                            :titulos="{
                                id: {titulo: 'ID', tipo: 'texto'},
                                nome: {titulo: 'Nome', tipo: 'texto'},
                                imagem: {titulo: 'Imagem', tipo: 'imagem'},
                                created_at: {titulo: 'Criação', tipo: 'data'},
                            }"
                        ></table-component>
                    </template>
                    <!-- template que contem o botão de adicionar que ativa um modal -->
                    <template v-slot:rodape>
                        <div class="row">
                            <!-- Componente de paginação -->
                            <div class="col-10">
                                <paginate-component>
                                    <li v-for="l, key in marcas.links" :key="key"
                                        :class="l.active ? 'page-item active' : 'page-item'"
                                        @click="paginacao(l)"
                                    >
                                        <a class="page-link" v-html="l.label"></a>
                                    </li>
                                </paginate-component>
                            </div>
                            <div class="col">
                            <button type="button" class="btn btn-primary btn-sm float-right" data-bs-toggle="modal" data-bs-target="#modalMarca">Adicionar</button>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!-- fim do card de listagem de marcas -->
            </div>
        </div>
        <!-- Modal para adicionar uma nova marca -->
        <modal-component id="modalMarca" titulo="Adicionar marca">
            <!-- Alertas do modal que contem condições de sucesso ou falha -->
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="transacaoDetalhes" titulo="Cadastro realizado com sucesso" v-if="transacaoStatus == 'adicionado'"></alert-component>
                <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao tentar cadastrar a marca" v-if="transacaoStatus == 'erro'"></alert-component>
            </template>
            <!-- Template que contem os inputs que são componentes vue -->
            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Nome da marca" id="novoNome" id-help="novoNomeHelp" texto-ajuda="Informe o nome da marca">
                        <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp" placeholder="Nome da marca" v-model="nomeMarca">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Imagem" id="novoImagem" id-help="novoImagemHelp" texto-ajuda="Selecione uma imagem no formato PNG">
                        <input type="file" class="form-control-file" id="novoImagem" aria-describedby="novoImagemHelp" placeholder="Selecione uma imagem" @change="carregarImagem($event)">
                    </input-container-component>
                </div>
            </template>
            <!-- Template que contem os botoes de fachar ou chamar o método de salvar -->
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
            </template>
        </modal-component>
        <!-- Modal de visualização de marca -->
        <modal-component id="modalMarcaVisualizar" titulo="Visualizar marca">
            <template v-slot:alertas>

            </template>
            <template v-slot:conteudo>
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>
                <input-container-component titulo="Nome">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                </input-container-component>
                <input-container-component titulo="Imagem">
                     <br>
                    <img :src="'/storage/'+ $store.state.item.imagem" class="img-fluid" :alt="'Imagem da marca' + $store.state.item.nome">
                </input-container-component>
                <input-container-component titulo="Criação">
                    <input type="text" class="form-control" :value="$store.state.item.created_at" disabled>
                </input-container-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </template>
        </modal-component>
        <!-- Modal de remoção de marca -->
        <modal-component id="modalMarcaRemover" titulo="Remover marca">
            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Remoção realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na tentiva de remover" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo>
                <input-container-component titulo="ID" v-if="$store.state.transacao.status != 'sucesso'">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>
                <input-container-component titulo="Nome">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                </input-container-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-danger" @click="remover()" v-if="$store.state.transacao.status != 'sucesso'">Remover</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </template>
        </modal-component>
        <!-- Modal de atualização de marca -->
        <modal-component id="modalMarcaAtualizar" titulo="Atualizar marca">
            <!-- Alertas do modal que contem condições de sucesso ou falha -->
            <template v-slot:alertas>

            </template>
            <!-- Template que contem os inputs que são componentes vue -->
            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Nome da marca" id="atualizarNome" id-help="atualizarNomeHelp" texto-ajuda="Informe o nome da marca">
                        <input type="text" class="form-control" id="atualizarNome" aria-describedby="atualizarNomeHelp" placeholder="Nome da marca" v-model="$store.state.item.nome">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Imagem" id="atualizarImagem" id-help="atualizarImagem" texto-ajuda="Selecione uma imagem no formato PNG">
                        <input type="file" class="form-control-file" id="atualizarImagem" aria-describedby="atualizarImagemHelp" placeholder="Selecione uma imagem" @change="carregarImagem($event)">
                    </input-container-component>
                </div>
            </template>
            <!-- Template que contem os botoes de fachar ou chamar o método de salvar -->
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="atualizar()">Atualizar</button>
            </template>
        </modal-component>
    </div>
</template>

<script>
    export default {
        computed: { // Computed é um objeto que contem funções que podem ser chamadas em qualquer lugar do template
            token() { // Função que retorna o token do usuário

                let token = document.cookie.split(';').find(indice => { // Função que busca o token no cookie
                    return indice.includes('token=') // Retorna o token
                })

                token = token.split('=')[1] // Separa o token do cookie
                token = 'Bearer ' + token // Adiciona o Bearer ao token

                return token // Retorna o token
            }
        },
        data() {
            return { // Data é um objeto que contem variáveis que podem ser chamadas em qualquer lugar do template
                urlBase: 'http://localhost:8000/api/v1/marca', // Url base da api
                urlPaginacao: '',
                urlFiltro: '',
                nomeMarca: '', // Variável que vai conter o nome da marca
                arquivoImagem: [], // Variável que vai conter o arquivo da imagem
                transacaoStatus: '', // Variável que vai conter o status da requisição
                transacaoDetalhes: {}, // Variável que vai conter os detalhes da requisição
                marcas: { data: [] }, // Variável que vai conter as marcas
                busca: { id: '', nome: ''  } // Variável que vai conter o valor da busca
            }
        },
        methods: { // Methods é um objeto que contem funções que podem ser chamadas em qualquer lugar do template
            atualizar() {
                let formData = new FormData() // Variável que vai conter o formulário da requisição
                formData.append('_method', 'PATCH') // Adiciona o método PUT no formulário
                formData.append('nome', this.$store.state.item.nome) // Adiciona o nome da marca no formulário

                if(this.arquivoImagem.length[0]){
                    formData.append('imagem', this.arquivoImagem[0]) // Adiciona a imagem no formulário caso ela exista
                }

                let config = { // Objeto que vai conter a configuração da requisição
                    headers: { // Objeto que vai conter os headers da requisição
                        'Authorization': this.token, // Adiciona o token no header da requisição
                        'Content-Type': 'multipart/form-data', // Adiciona o tipo de conteúdo no header da requisição
                        'Accept': 'application/json'
                    }
                }
                let url = this.urlBase + '/' + this.$store.state.item.id // Variável que vai conter a url da requisição

                axios.post(url, config, formData) // Requisição
                .then(response => { // Função que vai ser executada caso a requisição seja bem sucedida
                    //limpar o campo de seleção de arquivos
                    this.atualizarImagem = ''
                    this.carregarLista(); // Chama a função que carrega a lista de marcas
                })
                .catch({ // Função que vai ser executada caso a requisição seja mal sucedida

                })
            },
            remover(){
                let confirmacao = confirm('Deseja realmente remover a marca ' + this.$store.state.item.nome + '?') // Variável que vai conter a confirmação da remoção
                if(!confirmacao){ // Verifica se o usuário não confirmou a remoção
                    return false
                }
                let config = { // Objeto que vai conter a configuração da requisição
                    headers: { // Objeto que vai conter os headers da requisição
                        'Accept': 'application/json',
                        'Authorization': this.token // Adiciona o token no header da requisição
                    }
                }
                let url = this.urlBase + '/' + this.$store.state.item.id // Variável que vai conter a url da requisição

                let formData = new FormData() // Variável que vai conter o formulário da requisição
                formData.append('_method', 'DELETE') // Adiciona o método da requisição no formulário

                axios.post(url, formData, config)
                    .then(response => {
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = response.data.msg
                        this.carregarLista()
                    })
                    .catch(errors => {
                        this.$store.state.transacao.status = 'erro'
                        this.$store.state.transacao.mensagem = errors.response.data.erro
                    })
            },
            pesquisar(){ // Função que faz a busca de marcas por nome ou id
                let filtro = '' // Variável que vai conter o filtro da busca

                for(let chave in this.busca){ // Loop que percorre o objeto de busca
                    if(this.busca[chave]){ // Verifica se o valor da chave é diferente de vazio
                       if(filtro != ''){ // Verifica se a variável filtro já contem algum valor
                         filtro += ';' // Adiciona um ; na variável filtro que serve pra separar os filtros
                       }
                    filtro += chave + ':like:' + this.busca[chave] // Adiciona o filtro na variável filtro
                    }
                }
                if(filtro != ''){
                 this.urlPaginacao = 'page=1'
                 this.urlFiltro = '&filtro=' + filtro // Adiciona o filtro na url
                } else {
                 this.urlFiltro = ''
                }
                this.carregarLista() // Chama a função que carrega a lista de marcas
            },
            paginacao(l) { // Função que recebe os dados da paginação
               if(l.url){
                this.urlPaginacao = l.url.split('?')[1]
                this.carregarLista() // Chama a função que carrega a lista de marcas
               }
            },
            carregarLista() { // Função que carrega a lista de marcas e envia para a tabela

                let config = { // Configuração da requisição
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }
                let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro

                axios.get(url, config) // Requisição get para a api
                    .then(response => {
                        this.marcas = response.data // Atribui a resposta da api para a variável marcas
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            },
            carregarImagem(e) {
                this.arquivoImagem = e.target.files // Atribui o arquivo da imagem para a variável arquivoImagem
            },
            salvar() {
                console.log(this.nomeMarca, this.arquivoImagem[0]) // Exibe no console o nome da marca e o arquivo da imagem

                let formData = new FormData(); // Cria um objeto formData
                formData.append('nome', this.nomeMarca) // Adiciona o nome da marca no formData
                formData.append('imagem', this.arquivoImagem[0]) // Adiciona o arquivo da imagem no formData

                let config = { // Configuração da requisição
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                axios.post(this.urlBase, formData, config) // Requisição post para a api
                    .then(response => {
                        this.transacaoStatus = 'adicionado' // Atribui o status da requisição para adicionado
                        this.transacaoDetalhes = { // Atribui os detalhes da requisição
                            mensagem: 'ID do registro: ' + response.data.id // Atribui a mensagem de sucesso
                        }

                        console.log(response)
                    })
                    .catch(errors => {
                        this.transacaoStatus = 'erro'
                        this.transacaoDetalhes = {
                            mensagem: errors.response.data.message,
                            dados: errors.response.data.errors
                        }
                        //errors.response.data.message
                    })
            }
        },
        mounted() { // Mounted é uma função que é chamada quando o componente é montado
            this.carregarLista() // Chama a função que carrega a lista de marcas para a tabela
        }
    }
</script>
