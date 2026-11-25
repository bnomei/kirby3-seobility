<template>
  <div class="termsuggestion-wrapper">
    <k-field
        :disabled="disabled"
        :help="help"
        :label="headline"
        :required="required"
        :when="when"
        :class="['termsuggestion-field', hasSuggestions ? 'nobutton' : '']"
    >
      <k-button
          v-if="!hasSuggestions"
          :class="['k-button termsuggestion', loading ? 'loading' : '']"
          :icon="loading ? 'loader' : undefined"
          variant="filled"
          :disabled="hasChanges"
          @click="onClick"
      >
        {{ loading ? progress : label }}
      </k-button>
    </k-field>
    <k-box v-for="group in groups" :key="group.icon" :theme="group.theme" class="termsuggestion-info">
      <k-icon :type="group.icon" /> {{ group.terms.join(', ') }}
    </k-box>
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
    when: String,
  },
  data() {
    return { loading: false, hasSuggestions: false, less: [], ok: [], more: [] };
  },

  computed: {
    hasChanges() {
      return Object.keys(this.$panel.content.diff()).length > 0;
    },
    groups() {
      return [
        { terms: this.more, icon: 'add', theme: 'positive' },
        { terms: this.less, icon: 'remove', theme: 'negative' },
        { terms: this.ok, icon: 'check', theme: 'info' },
      ].filter(group => Array.isArray(group.terms) && group.terms.length);
    },
  },

  methods: {
    onClick() {
      this.loading = true
      this.$api.get('seobility/termsuggestion', {
        id: this.$panel.view.props.id,
        lang: this.$panel.language?.code || false,
      })
          .then(response => {
            this.more = response.more
            this.less = response.less
            this.ok = response.ok
            this.hasSuggestions = true
            this.loading = false
          })
          .catch(error => {
            this.hasSuggestions = false
            this.loading = false
          })
    }
  },
};
</script>

<style>
.termsuggestion.loading .k-button-text {
  opacity: .5;
}
.termsuggestion-field.nobutton .k-field-label {
  padding-bottom: 0;
}
.termsuggestion-info {
  display: flex;
  align-items: center;
  gap: var(--spacing-3);
  margin-top: var(--spacing-2);
}
</style>
