# Piedra, Papel y Tijera

# Construir el proyecto

Para construir el proyecto es necesario:

- Servidor web compatible con PHP
- Laravel
- Codigo fuente de la release

Con los siguientes requisitos cumplidos, solo se debe ejecutar el siguiente comando desde el terminal, estando en el directorio del proyecto.

```bash
composer update
```

# Rutas

## Administración (CRUD)

- Por defecto, las credenciales de administracíon son `root`, como usuario y contraseña.
- Se requiere tener credenciales de un usuario **Administrador**.

| Metodo | Ruta | Descripción | Cuerpo por defecto |
| --- | --- | --- | --- |
| GET | /api/admin/id | Obtener datos del usuario con ID id.  | Si |
| GET | /api/admin | Obtener datos de todos los usuarios.  | Si |
| POST | /api/admin | Añadir un nuevo usuario. | No |
| PUT | /api/admin/id | Modificar datos del usuario con ID id. | No |
| DELETE | /api/admin/id | Modificar datos del usuario con ID id. | Si |

## Jugar

- Se requiere un usuario para poder acceder.
- Un usuario puede acceder a una partida si falta un usuario en ella.
    
| Metodo | Ruta | Descripción | Cuerpo por defecto |
| --- | --- | --- | --- |
| POST | /api/play/create | Crear partida. | No |
| POST | /api/play/join | Entrar a la primera partida disponible. | Si |
| POST | /api/play/join/id | Entrar a la partida con ID id si es posible. | Si |
| POST | /api/play/6id | Jugar un turno en la partida con ID id. | No |

## Ranking

- Se requiere un usuario para poder acceder.


| Metodo | Ruta | Descripción | Cuerpo por defecto |
| --- | --- | --- | --- |
| GET | /api/play/create | Obtener el ranking de todos los usuarios, ordenados por partidas ganadas de forma descendente. | Si |
| GET | /api/play/createtop | Obtener el ranking de todos los usuarios, ordenados por partidas ganadas de forma descendente. Se indica en top cuantos usuarios se quieren obtener. | Si |
| GET | /api/play/joinranking/hands | Obtener el ranking de manos, ordenados por veces sacados de forma descendente. | Si |

# Cuerpos

### Cuerpo por defecto

```json
{
    "user": "root",
    "password": "root"
}
```

## Administración

### Modificar datos del usuario (`PUT`)

```json
{
    "user": "root",
    "password": "root",
		"newUserPassword": "root1",
		"newUserName": "root1",
		"newUserPlayed": "root", // Opcional
		"newUserWinned": "root", // Opcional
		"newUserRole": "root", // Opcional
		"newUserEnabled": "root", // Opcional
}
```

### Borrar un usuario (`DELETE`)

```json
{
    "user": "root",
    "password": "root"
}
```

## Partidas

### Crear partida

```json
{
    "user": "root",
    "password": "root",
    "maxRounds": "3"
}
```

### Jugar una partida

- Las manos que se pueden tirar son las mismas que en juego clasico
     | Id | Nombre |
     | --- | --- |
    | 1 | Piedra |
    | 2 | Papel |
    | 3 | Tijera |

```json
{
    "user": "root",
    "password": "root",
    "hand": 2
}
```

# Excepciones

| Codigo | Descripcion |
| --- | --- |
| 2 | El jugador ya está en la partida. |
| 3 | El jugador no existe. |
| 4 | No hay partidas libres. |
| 5 | Juego no encontrado. |
| 6 | El jugador ya ha jugado esta ronda. |
| 7 | Mano no valida. |
| 8 | Mano ya jugada. |
| 9 | Juego terminado |
| 10 | Rol no encontrado. |
