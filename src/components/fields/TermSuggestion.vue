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
    <k-info-field v-if="more !== undefined && more.length" theme="positive" :text="moreWithIcon" class="termsuggestion-info more" />
    <k-info-field v-if="less !== undefined && less.length" theme="negative" :text="lessWithIcon" class="termsuggestion-info less" />
    <k-info-field v-if="ok !== undefined && ok.length" theme="info" :text="okWithIcon" class="termsuggestion-info ok" />
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
    less: String,
    ok: String,
    more: String,
    when: String,
    hasSuggestions: { type: Boolean, default: false },
    loading: { type: Boolean, default: false },
  },

  computed: {
    hasChanges() {
      return Object.keys(this.$panel.content.changes()).length > 0;
    },
    moreWithIcon() {
      return '<div class="table"><div><svg data-size="32"><use xlink:href="#icon-add"></use></svg></div><div>' + this.more.join(', ') + '</div></div>'
    },
    lessWithIcon() {
      return '<div class="table"><div><svg data-size="32"><use xlink:href="#icon-remove"></use></svg></div><div>' + this.less.join(', ') + '</div></div>'
    },
    okWithIcon() {
      return '<div class="table"><div><svg data-size="32"><use xlink:href="#icon-check"></use></svg></div><div>' + this.ok.join(', ') + '</div></div>'
    },
  },

  methods: {
    onClick() {
      this.loading = true
      this.$api.get('seobility/termsuggestion', {
        id: this.$panel.view.props.id,
        lang: this.$panel?.$language?.code || false,
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
.termsuggestion-info .table {
  display: flex;
  justify-items: start;
  align-items: center;
}
.termsuggestion-info .table div:first-of-type {
  padding-top: 0.25rem;
  width: 2rem;
}
.termsuggestion-info .table div:first-of-type svg {
  width: 2rem;
  height: 2rem;
}
.termsuggestion-info.more .table div:first-of-type {
  fill: var(--color-positive-light);
}
.termsuggestion-info.less .table div:first-of-type {
  fill: var(--color-negative-light);
}
.termsuggestion-info.ok .table div:first-of-type {
  fill: var(--color-focus-light);
}
.termsuggestion-info .table div:last-of-type {
  padding-left: 1.5rem;
  font-size: var(--text-xs);
  line-height: 1rem;
}
</style>
