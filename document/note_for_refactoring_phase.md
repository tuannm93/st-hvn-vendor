# Note for refactoring phase of Sharing Tech

## Old code issues
1. One Action in the Controller handle lot of functions with `if () {...} else {...}`
2. Unused code commented
3. Comment with Japanese but some comments out of date with code logic

## Deal with old code issues when convent to new
1. Break to more action (create more routers) to handle diffence cases
2. Remove unused code commented
3. Translate to English keep the English comment in new source code. Remove out of date comments