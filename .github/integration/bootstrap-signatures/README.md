# Bootstrap signature regression

The embed runtime registers compiled classes during MINIT, before Composer's
request-time autoloader is available. The compiler's `PropertyAccessContext`
previously accepted `PhpParser\NodeAbstract` while `CompilerDiagnosticTrait`
accepted its parent interface `PhpParser\Node`. Checking that valid
contravariance requires loading both types. Zend cannot do that during MINIT:
Unix aborts during startup and Windows reports that NodeAbstract must be
registered before PropertyAccessContext.

The contract now accepts `Node`, matching its implementations and avoiding an
unnecessary variance lookup during module startup. This fixture compiles the
real interface with an implementation, then loads PhpParser during the request
and exercises the diagnostic with a real AST node. Before the fix it reproduces
the startup failure; after the fix it must print `bootstrap-signatures-ok` and
exit successfully.

After building the compiler, run inside the platform's protected build session:

```sh
PHP_COMPILER_JOBS=1 php bin/test-bootstrap-signatures.php --compiler=./tpc
```

Windows uses `--compiler=./tpc.exe`. Dedicated CI runners supply their available
CPU count through `PHP_COMPILER_JOBS`. Logs remain in
`build/bootstrap-signatures-<PHP_VERSION>`.
