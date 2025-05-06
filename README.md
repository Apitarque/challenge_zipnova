# Objetivo

Armar la arquitectura básica de un sistema de gestión de órdenes de venta de un ecommerce.

## Tarea

Armar una aplicación muy simple que incluya:

La estructura de modelos y tablas, con sus migraciones.

De las órdenes se debe almacenar sus datos básicos, como comprador, domicilio de entrega, datos de facturación, así como también de los productos vendidos en la orden.

Rutas, controladores y demas, para poder crear una orden por API y luego ver su detalle.

## Requisitos

Se debe utilizar Laravel como framework

Se debe utilizar MySQL como base de datos

Se debe subir a un repositorio y compartirlo

## Puntos extras si ...

Incluye la documentación de la API utilizando algún plugin, por ejemplo Open API.

Implementa el uso de cache, donde considere necesario, para evitar accesos redundantes a la DB

Implementa el uso de Jobs, por ejemplo para notificar via email la creación de la orden

Utiliza Policies, Request, Traits, Service etc.

Implementa algún Test Unitario o Test de Integración