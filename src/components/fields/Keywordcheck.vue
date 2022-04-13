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
      <k-button
          :link="url"
          slot="icon"
          :tooltip="$t('open')"
          :class="['k-input-icon-button keywordcheck', color]"
          tabindex="-1"
          target="_blank"
          :text="score"
      />
    </k-input>
  </k-field>
</template>

<script>
export default {
  props: {
    disabled: Boolean,
    help: String,
    label: String,
    required: Boolean,
    when: String,
    value: String,

    keywordcheck: Number,
    url: String,
  },

  /*
  data() {
    return {

    };
  },
  */

  computed: {
    hasChanges() {
      return this.$store.getters["content/hasChanges"]();
    },
    score() {
      return this.hasChanges ? '' : this.keywordcheck;
    },
    color() {
      if (!this.keywordcheck || this.hasChanges) {
        return 'white';
      }
      if (this.keywordcheck <= 50) {
        return 'red';
      }
      if (this.keywordcheck <= 80) {
        return 'yellow';
      }
      if (this.keywordcheck > 80) {
        return 'green';
      }
    },
  },

  methods: {
    onInput(value) {
      this.$emit("input", value);
    }
  },
};
</script>

<style>
  .keyword-wrapper {

  }
  .keyword-wrapper .keywordcheck {
    display: flex;
    background: var(--color-orange-400);
    color: #fff;
    overflow: hidden;
    align-items: center;
    justify-content: center;
    font-weight: 600;
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
  .keyword-wrapper .keywordcheck span {
    opacity: 1;
  }
</style>
