Per creare un nuovo "dominio" usare il task: "acHostDependant:createUser"
(es. php symfony acHostDependant:createUser nomedominio.it)

I File di configurazione verranno posti in %sf_root_dir%/users/nomedominio.it/config

Per far si che non venga chiesta la configurazione usare l'opzione --no_configuration
(es. php symfony acHostDependant:createUser --no_configuration nomedominio.it)


nel file config/databases.yml mettere:

all:
  doctrine:
    class: acHostDependantDoctrineDatabase
    param:
      dsn: 'mysql:host=localhost;dbname='

 
l'oggetto myUser deve ereditare da acHostDependatGuardSecurityUser invece che da sfGuardSecurityUser

sorry ancora prova
