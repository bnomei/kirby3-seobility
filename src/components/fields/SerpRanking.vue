<template>
  <div class="ranking-wrapper">
    <k-field
        v-if="rank === undefined"
        :disabled="disabled"
        :help="help"
        :label="headline"
        :required="required"
        :when="when"
    >
      <k-button
          :class="['k-button ranking', loading ? 'loading' : '']"
          :icon="loading ? 'loader' : undefined"
          variant="filled"
          :disabled="hasChanges"
          @click="onClick"
      >
        {{ loading ? progress : label }}
      </k-button>
    </k-field>
    <k-info-field v-if="rank !== undefined" :label="headline" :theme="theme" :text="info" class="ranking-info" />
  </div>
</template>

<script>
export default {
  props: {
    disabled: Boolean,
    help: String,
    headline: String,
    label: String,
    progress: String,
    notranked: { type: String, default: 'Page is not ranked.' },
    rank: Number,
    when: String,
    loading: { type: Boolean, default: false },
  },

  computed: {
    hasChanges() {
      return Object.keys(this.$panel.content.changes()).length > 0;
    },
    info() {
      return this.rank ?
          "<div class='table'><div>#" + this.rank + "</div><div><b>" + this.title + "</b><br>" + this.description + "</div></div>" :
          this.notranked
    },
    theme() {
      return this.rank > 0 ? 'info' : 'negative'
    }
  },

  methods: {
    onClick() {
      this.loading = true
      this.$api.get('seobility/ranking', {
        id: this.$panel.view.props.id,
        lang: this.$panel?.$language?.code || false,
      })
          .then(response => {
            this.rank = response.rank
            this.title = response.title
            this.description = response.description
            this.loading = false
          })
          .catch(error => {
            this.rank = undefined
            this.loading = false
          })
    }
  },
};
</script>

<style>
.ranking.loading .k-button-text {
  opacity: .5;
}
.ranking-info .table {
  display: flex;
  justify-items: start;
  align-items: center;
}
.ranking-info .table div:first-of-type {
  font-size: var(--text-3xl);
  color: var(--color-focus-light);
  padding-bottom: 0.25rem;
  font-variant: tabular-nums;
}
.ranking-info .table div:last-of-type {
  padding-left: 1.5rem;
  font-size: var(--text-xs);
  line-height: 1rem;
}
</style>
