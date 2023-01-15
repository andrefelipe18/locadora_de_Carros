<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <!-- Aqui estamos percorrendo os titulos da tabela -->
                    <th scope="col" v-for="t, key in titulos" :key="key">{{t.titulo}}</th>
                    <th v-if="visualizar.visivel || atualizar.visivel || remover.visivel"> </th>
                </tr>
            </thead>
            <tbody>
                <!-- Aqui estamos percorrendo os dados da tabela -->
                <tr v-for="obj, chave in dadosFiltrados" :key="chave">
                    <td v-for="valor, chaveValor in obj" :key="chaveValor">
                        <span v-if="titulos[chaveValor].tipo == 'texto'">{{valor}}</span>
                        <span v-if="titulos[chaveValor].tipo == 'data'">
                            {{ formattedDate(valor) }}
                        </span>
                        <span v-if="titulos[chaveValor].tipo == 'imagem'">
                            <img :src="'/storage/'+valor" width="30" height="30">
                        </span>
                    </td>
                    <td v-if="visualizar.visivel || atualizar.visivel || remover.visivel">
                        <button v-if="visualizar" class="btn btn-outline-primary btn-sm" :data-bs-toggle="visualizar.dataToggle" :data-bs-target="visualizar.dataTarget" @click="setStore(obj)">Visualizar</button>
                        <button v-if="atualizar.visivel" class="btn btn-outline-secondary btn-sm" :data-bs-toggle="atualizar.dataToggle" :data-bs-target="atualizar.dataTarget" @click="setStore(obj)">Editar</button>
                        <button v-if="remover.visivel" class="btn btn-outline-danger btn-sm" :data-bs-toggle="remover.dataToggle" :data-bs-target="remover.dataTarget" @click="setStore(obj)">Remover</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import { mapState } from 'vuex';
    export default {
        props: ['dados', 'titulos', 'atualizar', 'remover', 'visualizar'],
        methods: {
            setStore(obj) { //crie um método para setar os dados no store state item para serem utilizados no modal
                this.$store.state.transacao.status = ''
                this.$store.state.transacao.mensagem = ''
                this.$store.state.item = obj
            }
        },
        computed: {
            formattedDate() {
                // Formata a data para o formato dd/mm/yyyy com a hora hh:mm:ss
                return function (date) {
                    if (date) {
                        let data = new Date(date)
                        let dia = data.getDate().toString().padStart(2, '0')
                        let mes = (data.getMonth() + 1).toString().padStart(2, '0')
                        let ano = data.getFullYear()
                        let hora = data.getHours().toString().padStart(2, '0')
                        let minuto = data.getMinutes().toString().padStart(2, '0')
                        let segundo = data.getSeconds().toString().padStart(2, '0')
                        return `${dia}/${mes}/${ano} ${hora}:${minuto}:${segundo}`
                    }
                }
            },
            ...mapState({
            abcde: state => state.abcde
            }),
            dadosFiltrados() { //crie um método computado para filtrar os dados

                let campos = Object.keys(this.titulos) //pegue os campos que serão exibidos na tabela
                let dadosFiltrados = [] //crie um array para armazenar os dados filtrados

                this.dados.map((item, chave) => { //percorra os dados

                    let itemFiltrado = {} //crie um objeto para armazenar os dados filtrados de cada item
                    campos.forEach(campo => { //percorra os campos

                        itemFiltrado[campo] = item[campo] //utilizar a sintaxe de array para atribuir valores a objetos
                    })
                    dadosFiltrados.push(itemFiltrado) //adicione o objeto ao array
                })

                return dadosFiltrados //retorne um array de objetos
            }
        }
    }
</script>
