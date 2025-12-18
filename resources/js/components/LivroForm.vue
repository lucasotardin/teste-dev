<template>
    <div class="card p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0 text-primary fw-bold">
                <i class="fas fa-book me-2"></i>{{ modoEdicao ? 'Editar Livro' : 'Novo Livro' }}
            </h5>
            <button type="button" class="btn-close" aria-label="Close" @click="cancelar"></button>
        </div>
        
        <form @submit.prevent="handleSubmit">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nome da Obra</label>
                    <input v-model="form.nome" class="form-control" placeholder="Ex: O Senhor dos Anéis" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Autor</label>
                    <input v-model="form.autor" class="form-control" placeholder="Ex: J.R.R. Tolkien" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Categoria</label>
                    <select v-model="form.categoria" class="form-select" required>
                        <option value="" disabled>Selecione uma Categoria</option>
                        <option v-for="cat in categorias" :key="cat" :value="cat">{{ cat }}</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Código (Único)</label>
                    <input v-model="form.codigo" class="form-control" placeholder="Ex: ISBN-1234" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tipo</label>
                    <select v-model="form.tipo" class="form-select" required>
                        <option value="" disabled>Selecione o Tipo</option>
                        <option value="Digital">Arquivo Digital</option>
                        <option value="Físico">Livro Físico</option>
                    </select>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">{{ tamanhoLabel }}</label> 
                    
                    <input 
                        type="number" 
                        step="0.01" 
                        min="0"
                        v-model="form.tamanho" 
                        class="form-control" 
                        required
                        placeholder="Digite apenas números (Ex: 1.5)"
                    >
                    <small class="text-muted">Informe apenas o valor numérico.</small>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                <button type="button" @click="cancelar" class="btn btn-light me-2 text-secondary fw-bold">Cancelar</button>
                <button type="submit" :disabled="isSubmitting" :class="modoEdicao ? 'btn btn-warning fw-bold' : 'btn btn-success fw-bold'">
                    <i class="fas fa-save me-1"></i>
                    {{ isSubmitting ? 'Salvando...' : (modoEdicao ? 'Salvar Alterações' : 'Cadastrar Livro') }}
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

export default {
    data() {
        return {
            form: {
                id: null,
                nome: '',
                autor: '',
                categoria: '',
                codigo: '',
                tipo: 'Digital', // Valor padrão
                tamanho: '',
            },
            tamanhoLabel: 'Tamanho do Arquivo (MB/GB)', // Label padrão
            isSubmitting: false,
            modoEdicao: false
        };
    },
    computed: {
        ...mapGetters({
            categorias: 'allCategorias',
            livroParaEditar: 'livroEmEdicao' 
        })
    },
    // AQUI ESTÁ A MÁGICA DO WATCH
    watch: {
        'form.tipo': {
            immediate: true, // Executa assim que o componente abre
            handler(novoValor) {
                if (novoValor === 'Físico') {
                    this.tamanhoLabel = 'Peso do Livro (em Kg)';
                } else {
                    this.tamanhoLabel = 'Tamanho do Arquivo (em MB)';
                }
            }
        }
    },
    mounted() {
        if (this.livroParaEditar) {
            this.form = { ...this.livroParaEditar };
            this.modoEdicao = true;
        }
    },
    methods: {
        ...mapActions(['createLivro', 'updateLivro', 'limparLivroEdicao']), 
        
        async handleSubmit() {
            this.isSubmitting = true;

            // Garantia de formatação correta do Tipo
            let tipoCorrigido = this.form.tipo;
            if (tipoCorrigido && tipoCorrigido.toLowerCase().includes('físico')) {
                tipoCorrigido = 'Físico';
            } else {
                tipoCorrigido = 'Digital';
            }

            const data = { ...this.form, tipo: tipoCorrigido };
            const action = this.modoEdicao ? 'updateLivro' : 'createLivro';
            
            try {
                await this.$store.dispatch(action, data);
                alert(this.modoEdicao ? "Livro atualizado!" : "Livro cadastrado!");
                this.limparERechar();
            } catch (error) {
                const errorMsg = error.response?.data?.message || "Erro ao salvar livro.";
                // Se tiver erros de validação específicos, mostra o primeiro
                const fieldErrors = error.response?.data?.errors;
                let detalhe = "";
                if(fieldErrors) {
                    detalhe = "\n" + Object.values(fieldErrors).flat().join("\n");
                }
                alert(errorMsg + detalhe);
            } finally {
                this.isSubmitting = false;
            }
        },

        cancelar() {
            this.limparERechar();
        },

        limparERechar() {
            this.limparLivroEdicao();
            this.form = { id: null, nome: '', autor: '', categoria: '', codigo: '', tipo: 'Digital', tamanho: '' };
            this.modoEdicao = false;
            this.$emit('close'); 
        }
    },
};
</script>