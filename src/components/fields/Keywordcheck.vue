<template>
  <k-field
      class="keyword-wrapper"
      :disabled="disabled"
      :help="help"
      :label="label"
      :required="required"
      :when="when"
  >
    <k-input
      theme="field"
      type="text"
      :value="value"
      @input="onInput"
    >
      <template #icon>
        <k-button
          :link="result.url"
          :tooltip="$t('open')"
          :class="['k-button k-input-icon-button keywordcheck', color]"
          variant="filled"
          tabindex="-1"
          target="_blank"
          :text="scoreDyn"
        />
      </template>
    </k-input>
    <div v-if="!paid" class="keywordlink">powered by <a href="https://www.seobility.net/en/?ref=kirby3-seobility-plugin" target="_blank">Seobility.net</a></div>
  </k-field>
</template>

<script>
export default {
  emits: ['input'],
  props: {
    disabled: Boolean,
    help: String,
    label: String,
    required: Boolean,
    when: String,
    value: String,
    score: Number,
    url: String,
    paid: { type: Boolean, default: false },
  },

  data() {
    return { loading: false, fetched: null };
  },

  watch: {
    hasChanges() {
      // post save or on revert
      if (!this.hasChanges) {
        this.syncContent()
      }
    }
  },

  computed: {
    result() {
      return this.fetched || { score: this.score, url: this.url };
    },
    hasChanges() {
      return Object.keys(this.$panel.content.diff()).length > 0;
    },
    scoreDyn() {
      return this.hasChanges || this.loading ? '' : String(this.result.score);
    },
    color() {
      if (!this.result.score || this.hasChanges) {
        return 'white';
      }
      if (this.result.score <= 30) {
        return 'red';
      }
      if (this.result.score <= 80) {
        return 'yellow';
      }
      if (this.result.score > 80) {
        return 'green';
      }
    },
  },

  methods: {
    onInput(value) {
      this.$emit("input", value);
    },
    syncContent() {
      this.loading = true
      this.$api.get('seobility/keywordcheck', {
        id: this.$panel.view.props.id,
        lang: this.$panel.language?.code || false,
      })
      .then(response => {
        this.fetched = response
        this.loading = false
      })
      .catch(error => {
        this.loading = false
      })
    }
  },
};
</script>

<style>
  .keyword-wrapper {

  }
  .keyword-wrapper .keywordcheck.red {
    background: var(--color-red)
  }
  .keyword-wrapper .keywordcheck.yellow {
    background: var(--color-yellow)
  }
  .keyword-wrapper .keywordcheck.green {
    background: var(--color-green)
  }
  .keyword-wrapper .keywordlink {
    padding-top: 0.25rem;
    padding-right: 0.125rem;
    text-align: right;
    font-size: var(--text-xs);
    color: var(--color-gray-600);
  }
  .keyword-wrapper .keywordlink a,
  .keyword-wrapper .keywordlink a:hover,
  .keyword-wrapper .keywordlink a:visited,
  .keyword-wrapper .keywordlink a:active {
    color: var(--color-gray-900);
    font-weight: var(--font-bold);
    text-decoration: underline;
  }
</style>
