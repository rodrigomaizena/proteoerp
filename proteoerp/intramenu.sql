-- MySQL dump 10.11
--
-- Host: localhost    Database: matbar
-- ------------------------------------------------------
-- Server version	5.0.45

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `intramenu`
--

DROP TABLE IF EXISTS `intramenu`;
CREATE TABLE `intramenu` (
  `modulo` varchar(10) NOT NULL default '',
  `titulo` varchar(30) default NULL,
  `mensaje` varchar(60) default NULL,
  `panel` varchar(30) default NULL,
  `ejecutar` varchar(80) default 'UNICO',
  `target` varchar(10) default NULL,
  `imagen` varchar(100) default NULL,
  `visible` char(1) default NULL,
  `pertenece` varchar(10) default NULL,
  `orden` tinyint(4) default NULL,
  `ancho` int(10) unsigned default '800',
  `alto` int(10) unsigned default '600',
  PRIMARY KEY  (`modulo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `intramenu`
--

LOCK TABLES `intramenu` WRITE;
/*!40000 ALTER TABLE `intramenu` DISABLE KEYS */;
INSERT INTO `intramenu` VALUES ('901','Usuarios','Usuarios','CONFIGURACION','/supervisor/usuarios','popu',NULL,'S','9',NULL,800,600),('9','Supervisor','Parametrizacion, usuarios, accesos','UNICO','','self',NULL,'S',NULL,NULL,800,600),('137','Zonas de Ventas','Zonas de Ventas','REGISTROS','ventas/zona','popu',NULL,'S','1',NULL,800,600),('131','Clientes','Clientes','REGISTROS','/ventas/scli','popu',NULL,'S','1',NULL,800,600),('601','Reglas','Reglas Contables','GENERADOR','/contabilidad/reglas','popu',NULL,'S','6',NULL,800,600),('6','Contabilidad','Reglas, Generar contabilidad ...','UNICO','','self','','S',NULL,NULL,800,600),('501','Inventario de Gastos','Inventario de Gastos','GASTOS','finanzas/invgasto','popu',NULL,'S','5',NULL,800,600),('230','Proveedores','Proveedores','REGISTROS','compras/sprv','popu',NULL,'S','2',NULL,800,600),('3','Inventarios','Recepcion de Mercancia, Proveedores','UNICO','','self',NULL,'S',NULL,NULL,800,600),('504','Tributos','Tributos e Impuestos','OBLIGACIONES','finanzas/libros','popu',NULL,'S','5',NULL,800,600),('90E','Muro','Muro','UNICO','supervisor/muro','popu',NULL,'S','9',NULL,800,600),('712','Nomina','Nomina','TRANSACCIONES','/nomina/nomina','popu',NULL,'S','7',NULL,800,600),('20A','Compras farmacia','Compras de Farmacia','FARMACIA','farmacia/scst','popu',NULL,'S','2',NULL,800,600),('5','Finanzas','Bancos, Gastos, Pagos','UNICO','','self',NULL,'S',NULL,NULL,800,600),('205','Orden de importacion','Orden de importacion','IMPORTACION','import/ordi','popu',NULL,'S','2',NULL,1024,600),('001','Noticias','Noticias o Informacion empresarial','INICIO','supervisor/noticias','popu',NULL,'S','0',NULL,800,600),('002','Documentación','Manuales y tutorias','INICIO',NULL,NULL,NULL,'S','0',NULL,800,600),('1','Ventas','Facturación, Presupuestos, Pedidos, Notas de Entrega','UNICO','index.php','self','','S',NULL,NULL,800,600),('902','Valores','Varores constantes del sistema','CONFIGURACION','/supervisor/valores','popu',NULL,'S','9',NULL,800,600),('903','Logotipo','Asignar o cambiar el logotipo de la empresa','CONFIGURACION','supervisor/logo','popu',NULL,'S','9',NULL,800,600),('7','Nomina','Pagos, Cargos, Accesos ...','UNICO','','self',NULL,'S',NULL,NULL,800,600),('103','Facturación','Ventas de Mercancia','TRANSACCIONES','ventas/factura','popu','','S','1',NULL,800,600),('104','Presupuestos','Otros Ingresos','TRANSACCIONES','/ventas/presupuestos','popu',NULL,'S','1',NULL,800,600),('300','Kardex Supermercado','Kardex Supermercado','CONSULTAS','/supermercado/kardex','popu',NULL,'S','3',NULL,800,600),('107','Notas de Entrega','Grafico de Ventas','TRANSACCIONES','/ventas/snte','popu',NULL,'S','1',NULL,800,600),('204','Aranceles','Aranceles','IMPORTACION','import/aran','popu',NULL,'S','2',NULL,800,600),('202','Cuentas por Pagar','cuentas por pagar','TRANSACCIONES','','self',NULL,'S','2',NULL,800,600),('2','Compras','Estados de Cuenta, Cobros,','UNICO','','self',NULL,'S',NULL,NULL,800,600),('201','Compras','Compra de Inventario.','TRANSACCIONES','/compras/scst','popu',NULL,'S','2',NULL,800,600),('109','Notas de Despacho','Notas de Despacho','TRANSACCIONES','/ventas/notadespacho','popu',NULL,'S','1',NULL,800,600),('701','Cargos','Cargos de nomina','REGISTROS','nomina/carg','popu',NULL,'S','7',NULL,800,600),('904','Accesos','Asignar Accesos','CONFIGURACION','accesos','popu',NULL,'S','9',NULL,800,600),('905','Menu de opciones','Modificar el menu','CONFIGURACION','/supervisor/menu','popu',NULL,'S','9',NULL,800,600),('906','Usuarios','Usuarios del sistema','CONFIGURACION','/supervisor/usuarios','popu',NULL,'S','9',NULL,800,600),('904001','Crear Acceso','Crear Acceso','CONFIGURACION','accesos/crear',NULL,NULL,'N','904',NULL,800,600),('302','Transferencias','Transferencias de inventario','TRANSACCIONES','inventario/stra','popu',NULL,'S','3',NULL,800,600),('133','Formas de Pago','Formas de Pago','REGISTROS','ventas/tarjeta','popu',NULL,'S','1',NULL,800,600),('702','Asignación','Asignacion','REGISTROS','nomina/asig','popu',NULL,'S','7',NULL,800,600),('703','Aumentos de Sueldo','Aumentos de Sueldo','REGISTROS','nomina/aumentosueldo','popu',NULL,'S','7',NULL,800,600),('134','Vendedores','Vendedores y Cobradores','REGISTROS','ventas/vend','popu',NULL,'S','1',NULL,800,600),('135','Cajeros','Cajeros','REGISTROS','ventas/scaj','popu',NULL,'S','1',NULL,800,600),('136','Cajas','Configuracion de Cajas','REGISTROS','ventas/caja','popu',NULL,'S','1',NULL,800,600),('132','Grupo de Clientes','Grupo de Clientes','REGISTROS','ventas/grcl','popu',NULL,'S','1',NULL,800,600),('704','Conceptos','Conceptos','REGISTROS','nomina/conc','popu',NULL,'S','7',NULL,800,600),('705','División','División','REGISTROS','nomina/divi','popu',NULL,'S','7',NULL,800,600),('706','Departamento','Departamento','REGISTROS','nomina/depa','popu',NULL,'S','7',NULL,800,600),('707','Ficha del trabajador','Ficha del trabajador','REGISTROS','nomina/pers','popu',NULL,'S','7',NULL,800,600),('708','Prestamos','Prestamos','TRANSACCIONES','nomina/prestamos','popu',NULL,'S','7',NULL,800,600),('709','Accesos del Personal','Accesos del Personal','REGISTROS','nomina/accesos','popu',NULL,'S','7',NULL,800,600),('701001','Modificar','Modificar Registro','UNICO','',NULL,NULL,'S','701',NULL,800,600),('710','Adelantos de Prestaciones','Adelantos de Prestaciones','TRANSACCIONES','nomina/pres','popu',NULL,'S','7',NULL,800,600),('711','Calendario','Calendario','REGISTROS','nomina/calendario','popu',NULL,'S','7',NULL,800,600),('505','Anticipos','Anticipos','CARTERA','finanzas/apan','popu',NULL,'S','5',NULL,800,600),('506','Cruce de Cuentas','Cruce de Cuentas','CARTERA','finanzas/cruc','popu',NULL,'S','5',NULL,800,600),('507','Mov. de Proveedores','Mov. de Proveedores','CARTERA','finanzas/movproveedores','popu',NULL,'S','5',NULL,800,600),('508','Obligaciones IVA','Obligaciones IVA','OBLIGACIONES','finanzas/siva','popu',NULL,'S','5',NULL,800,600),('509','Cambio de Iva','Cambio de Iva','OBLIGACIONES','finanzas/civa','popu',NULL,'S','5',NULL,800,600),('305','Grupos de Inventario','Grupos de Inventario','REGISTROS','inventario/grup','popu',NULL,'S','3',NULL,800,600),('306','Líneas de Inventario','Líneas de Inventario','REGISTROS','inventario/line','popu',NULL,'S','3',NULL,800,600),('307','Almacenes','Almacenes','REGISTROS','inventario/caub','popu',NULL,'S','3',NULL,800,600),('308','Seriales','Seriales','REGISTROS','inventario/seriales','popu',NULL,'S','3',NULL,800,600),('309','Departamentos','Departamentos','REGISTROS','inventario/dpto','popu',NULL,'S','3',NULL,800,600),('113','Reportes de Vendedores','Reportes de Vendedores','REPORTES','/reportes/index/pers','popu',NULL,'S','1',NULL,800,600),('713','Reportes del Personal','Reportes del Personal','REPORTES','/reportes/index/pers','popu',NULL,'S','7',NULL,800,600),('116','Reportes de Zonas','Reportes de Zonas','REPORTES','/reportes/index/zona','popu',NULL,'S','1',NULL,800,600),('502','Movimiento de Clientes','Reportes de Movimiento de Clientes','REPORTES','/reportes/index/smov','popu',NULL,'S','5',NULL,800,600),('303','Reportes de Inventario','Reportes de Inventario','REPORTES','/reportes/index/sinv','popu',NULL,'S','3',NULL,800,600),('606','Cierre Contable','Cierre Contable','GENERADOR','/contabilidad/cierre','popu',NULL,'S','6',NULL,800,600),('510','Grupos de Gastos','Grupos de Gastos','GASTOS','finanzas/grga','popu',NULL,'S','5',NULL,800,600),('511','Maestro de Gastos','Maestro de Gastos','GASTOS','finanzas/mgas','popu',NULL,'S','5',NULL,800,600),('512','Cajas y Bancos','Cajas y Bancos','BANCOS','finanzas/banc','popu',NULL,'S','5',NULL,800,600),('603','Reportes de Contabilidad','Reportes de Contabilidad','REPORTES','/reportes/index/casi','popu',NULL,'S','6',NULL,800,600),('515','Retenciones','Retenciones','OBLIGACIONES','finanzas/rete','popu',NULL,'S','5',NULL,800,600),('101','Análisis de ventas','Análisis de ventas','CONSULTAS','ventas/analisis','popu',NULL,'S','1',NULL,1000,600),('106','Ventas mensuales','Ventas mensuales','CONSULTAS','ventas/mensuales','popu',NULL,'S','1',NULL,800,600),('8','Hospitalidad','Reservaciones,habitaciones, checkin, checkout...','UNICO',NULL,NULL,NULL,'S',NULL,NULL,800,600),('808','Huespedes','Huespedes','REGISTROS','/hospitalidad/huesped','popu',NULL,'S','8',NULL,800,600),('516','Grupos de partida','Grupos de partida','OBRAS','construccion/gpartida','popu',NULL,'S','5',NULL,800,600),('517','Partidas de obra','Partidas de obra','OBRAS','construccion/partida','popu',NULL,'S','5',NULL,800,600),('108','Cierre de caja','Cierre de caja','PUNTO DE VENTA','supermercado/cierre','popu',NULL,'S','1',NULL,800,600),('122','Otros Ingresos','Otros Ingresos','TRANSACCIONES','/ventas/otin','popu',NULL,'S','1',NULL,800,600),('317','Kardex de Inventario','Kardex de Inventario','CONSULTAS','inventario/kardex','popu',NULL,'S','3',NULL,800,600),('519','Reportes de Bancos','Reportes de Bancos','REPORTES','/reportes/index/banc','popu',NULL,'S','5',NULL,800,600),('520','Reportes de Gastos','','REPORTES','/reportes/index/gser','popu',NULL,'S','5',NULL,800,600),('102','Reportes de Facturacion','Reportes de Facturacion','REPORTES','/reportes/index/sfac','popu',NULL,'S','1',NULL,800,600),('110','Ventas Anuales','Ventas Anuales','CONSULTAS','/ventas/anuales','popu',NULL,'S','1',NULL,800,600),('521','Movimiento de Proveedores','Reportes de Movimiento Proveedores','REPORTES','/reportes/index/sprm','popu',NULL,'S','5',NULL,800,600),('111','Despacho Express','Despacho Express','TRANSACCIONES','/ventas/sfacdesp','popu',NULL,'S','1',NULL,800,600),('112','Reporte de Despacho','Reporte de Despacho','REPORTES','/ventas/lsfacdesp','popu',NULL,'S','1',NULL,800,600),('301','Maestro','Maestro de Invetnario','REGISTROS','/inventario/sinv','popu',NULL,'S','3',NULL,800,600),('114','Consulta de Caja','Consulta de Caja','PUNTO DE VENTA','supermercado/poscuadre','popu',NULL,'S','1',NULL,800,600),('115','Análisis de Ventas','Análisis de Ventas','PUNTO DE VENTA','supermercado/posfact','popu',NULL,'S','1',NULL,800,600),('602','Generar Contabilidad','Generar Contabilidad','GENERADOR','/contabilidad/generar','popu',NULL,'S','6',NULL,800,600),('604','Plan de Cuentas','Plan de Cuentas','REGISTROS','/contabilidad/cpla','popu',NULL,'S','6',NULL,800,600),('605','Configurar','Configurar Contabilidad','REGISTROS','/contabilidad/configurar','popu',NULL,'S','6',NULL,800,600),('503','Movimiento de Bancos','Movimiento de Bancos','REPORTES','/reportes/index/bmov','popu',NULL,'S','5',NULL,800,600),('117','Reportes de Clientes','Reportes de Clientes','REPORTES','/reportes/index/scli','popu',NULL,'S','1',NULL,800,600),('513','Cruce de Cuentas','Cruce de Cuentas','REPORTES','/reportes/index/cruc','popu',NULL,'S','5',NULL,800,600),('118','Notas de Despacho','Notas de Despacho','REPORTES','/ventas/notadespacho','popu',NULL,'S','1',NULL,800,600),('514','Formas de Pago','Formas de Pago','REPORTES','/reportes/index/tarjeta','popu',NULL,'S','5',NULL,800,600),('802','Restaurante','Repotes de restaurant','REPORTES','/reportes/index/menu','popu',NULL,'S','8',NULL,800,600),('607','Asientos','Asientos Contables','TRANSACCIONES','/contabilidad/casi','popu',NULL,'S','6',NULL,800,600),('803','Hotel','Reportes de Hotel','REPORTES','/reportes/index/habi','popu',NULL,'S','8',NULL,800,600),('804','Reservaciones','Reservaciones','REPORTES','/reportes/index/hres','popu',NULL,'S','8',NULL,800,600),('304','Grupos de Inventario','GRUP ','REPORTES','/reportes/index/grup','popu',NULL,'S','3',NULL,800,600),('805','Facturación Hotel','Facturación Hotel','REPORTES','/reportes/index/hfac','popu',NULL,'S','8',NULL,800,600),('714','Reportes de Nomina','Reportes de Nomina','REPORTES','/reportes/index/nomi','popu',NULL,'S','7',NULL,800,600),('806','Menú','Menú de Restaurante','RESTAURANTE','/hospitalidad/carta','popu',NULL,'S','8',NULL,800,600),('807','Grupo Menú','Grupo Menú del Restaurante','RESTAURANTE','/hospitalidad/grupomenu','popu',NULL,'S','8',NULL,800,600),('907','Control de Bitácora','Control de Bitácora','UNICO','/supervisor/bitacora','popu',NULL,'S','9',NULL,800,600),('907001','supervisor de bitacora','supervisor de bitacora','UNICO','','self',NULL,'S','907',NULL,800,600),('715','Contratos Laborales','Contratos Laborales','TRANSACCIONES','/nomina/noco','popu',NULL,'S','7',NULL,800,600),('100','Clientes','Clientes','CONSULTAS','ventas/clientes','popu',NULL,'S','1',NULL,800,600),('120','Pedidos de Clientes','Pedidos de Clientes','TRANSACCIONES','ventas/pfac','popu',NULL,'S','1',NULL,800,600),('203','Orden de Compra','orden de compra','TRANSACCIONES','/compras/ordc','popu',NULL,'S','2',NULL,800,600),('518','Egresos','Egresos','GASTOS','finanzas/gser','popu',NULL,'S','5',NULL,800,600),('231','Grupo de Proveedores','Grupo de Proveedores','REGISTROS','compras/grpr','popu',NULL,'S','2',NULL,800,600),('522','Orden de Servicio','orden de servicio','GASTOS','/finanzas/ords','popu',NULL,'S','5',NULL,800,600),('207','Reportes de Compras','Reportes de Compras','REPORTES','/reportes/index/scst','popu',NULL,'S','2',NULL,800,600),('206','Proveedores Ocasionales','Proveedores Ocasionales','REGISTROS','/finanzas/provoca','popu',NULL,'S','2',NULL,800,600),('121','Club de compras','Club de compras','PUNTO DE VENTA','supermercado/club','popu',NULL,'S','1',NULL,800,600),('123','Ventas en vivo','Ventas en vivo','PUNTO DE VENTA','/supermercado/envivo','popu',NULL,'S','1',NULL,800,600),('124','Ganancias','Ganancias ','CONSULTAS','/ventas/ganancias','popu',NULL,'S','1',NULL,800,600),('908','Tickets','Tickets','SOPORTE','/supervisor/tiket','popu',NULL,'S','9',NULL,800,600),('908001','supervisor de tiket','supervisor de tiket','SOPORTE','',NULL,NULL,NULL,'908',NULL,800,600),('125','Ventas Vendedores','Ventas Vendedores','CONSULTAS','/ventas/vendedores','popu',NULL,'S','1',NULL,800,600),('209','Productos','Productos','CONSULTAS','compras/productos','popu',NULL,'S','2',NULL,800,600),('208','Compras  a proveedores','Compras  a proveedores','CONSULTAS','/compras/proveedores','popu',NULL,'S','2',NULL,800,600),('310','Fotos de productos','Fotos de productos','REGISTROS','inventario/fotos','popu',NULL,'S','3',NULL,800,600),('311','Catalogo','Catalogo de Inventario','REPORTES','inventario/gfotos','popu',NULL,'S','3',NULL,800,600),('128','Traer ventas Caja','Traer ventas de  Caja','PUNTO DE VENTA','/supermercado/traer/cajas','popu',NULL,'S','1',NULL,800,600),('312','Código de Barras','Código de Barras','REGISTROS','inventario/barras','popu',NULL,'S','3',NULL,800,600),('129','Traer ventas Sucursales','Traer ventas Sucursales','SUPERMERCADO','/supermercado/traer/sucursales','popu',NULL,'S','1',NULL,800,600),('801','Reservaciones','Reservaciones','REGISTROS','/hospitalidad/reserva','popu',NULL,'S','8',NULL,800,600),('809','Tarifa Telefónica','Tarifa  Telefónica','REGISTROS','/hospitalidad/taritele','popu',NULL,'S','8',NULL,800,600),('810','Precios','Precios','REGISTROS','/hospitalidad/precios','popu',NULL,'S','8',NULL,800,600),('811','Habitaciones','Habitaciones','REGISTROS','/hospitalidad/habita','popu',NULL,'S','8',NULL,800,600),('812','Clientes','Clientes','REGISTROS','/hospitalidad/clientesp','popu',NULL,'S','8',NULL,800,600),('813','Servicios','Servicios','REGISTROS','/hospitalidad/servus','popu',NULL,'S','8',NULL,800,600),('313','Problema  en inventario','Detecta posibles problemas en inventario','CONSULTAS','/inventario/fallas','popu',NULL,'S','3',NULL,800,600),('130','Cupones','Cupones','SUPERMERCADO','/supermercado/cupones','popu',NULL,'S','1',NULL,800,600),('814','Comanda','comanda','RESTAURANTE','hospitalidad/restaurante','popu',NULL,'S','8',NULL,800,600),('815','Frontdesk','Frontdesk','GESTION','/hospitalidad/frontdesk','popu',NULL,'S','8',NULL,800,600),('200','Compras ','Compras','CONSULTAS','compras/gcompras','popu',NULL,'S','2',NULL,800,600),('139','Venta de Productos','Venta de Productos','CONSULTAS','/ventas/productos','popu',NULL,'S','1',NULL,800,600),('141','Ventas ','Ventas ','CONSULTAS','/ventas/ventas','popu',NULL,'S','1',NULL,800,600),('816','Mesoneros','Mesoneros','RESTAURANTE','/hospitalidad/mesoneros','popu',NULL,'S','8',NULL,800,600),('909','Log de Usuarios','Log de Usuarios','UNICO','/supervisor/logusu','popu',NULL,'S','9',NULL,800,600),('1310','Límite de Credito','Límite de Credito','REGISTROS','pelusa()','javascript',NULL,'S','131',NULL,800,600),('1311','Control','Prueba','REGISTROS','alert(\"Hola prueba\");','javascript',NULL,'S','131',NULL,800,600),('5220','Tabla de Bancos','Tabla de Bancos','GASTOS','finanzas/tbanco','popu',NULL,'S','522',NULL,800,600),('500','Tabla de Bancos','Tabla de Bancos','BANCOS','finanzas/tban','popu',NULL,'S','5',NULL,800,600),('50A','Variaciones FP','Variaciones de la forma de pago','BANCOS','finanzas/tardet','popu',NULL,'S','5',NULL,800,600),('900','Mantenimiento','Mantenimiento','UNICO','supervisor/mantenimiento','popu',NULL,'S','9',NULL,800,600),('8140','Devoluciones','Devoluciones','RESTAURANTE','','self',NULL,'N','814',NULL,800,600),('10F','Bancos','Bancos','CONSULTAS','supermercado/bancos','popu',NULL,'S','1',NULL,800,600),('1030','Imprimir','Imprimir factura','TRANSACCIONES','formatos/ver/FACTURA/<#5#>/<#6#>','self',NULL,'S','103',NULL,800,600),('1040','Imprimir','Imprimir Presupuesto','TRANSACCIONES','/formatos/ver/PRESUP/<#5#>','self',NULL,'S','104',NULL,800,600),('2010','Imprimir','Imprimir Compra','UNICO','/formatos/ver/COMPRA/<#5#>','self',NULL,'S','201',NULL,800,600),('1070','Imprimir','Imprimir Nota de Entrega','TRANSACCIONES','/formatos/ver/SNTE/<#5#>','self',NULL,'S','107',NULL,800,600),('1090','Imprimir','Imprimir Nota de Despacho','TRANSACCIONES','formatos/ver/SNOT/<#5#>','self',NULL,'S','109',NULL,800,600),('5180','Imprimir','Imprimir Egresos','GASTOS','/formatos/ver/GSER/<#6#>','self',NULL,'S','518',NULL,800,600),('2030','Imprimir','Imprimir orde de Compra','UNICO','formatos/ver/ORDC/<#5#>','self',NULL,'S','203',NULL,800,600),('5060','Imprimir','Imprimir Cruce de Cuentas','CARTERA','formatos/ver/CRUCDE/<#5#>','self',NULL,'S','506',NULL,800,600),('1220','Imprimir','Imprimir otros ingresos','TRANSACCIONES','formatos/ver/OTINND/<#6#>','self',NULL,'S','122',NULL,800,600),('5221','Imprimir','Imprimir Orden de Servicio','GASTOS','formatos/ver/ORDCBC/<#5#>','self',NULL,'S','522',NULL,800,600),('1200','Imprimir','Imprimir Pedido de Cliente','TRANSACCIONES','formatos/ver/PFAC/<#5#>','self',NULL,'S','120',NULL,800,600),('90A','Documentación Técnica','Documentación Técnica','SOPORTE','supervisor/docu','popu',NULL,'S','9',NULL,800,600),('50B','Gastos','Gastos','CONSULTAS','finanzas/ggastos','popu',NULL,'S','5',NULL,800,600),('90B','Gestión de Reportes','Menu de Reportes','SOPORTE','supervisor/repomenu','popu',NULL,'S','9',NULL,1024,600),('105','Reportes de Restaurante','Reportes de Restaurante','REPORTES','reportes/index/rfac','popu',NULL,'S','1',NULL,800,600),('1312','Cambio de Código','Cambio de Código','REGISTROS','alert(\"Hola Fusion\");','popu',NULL,'S','131',NULL,800,600),('10A','Ventas Supermercado','Ventas Supermercado','CONSULTAS','supermercado/ventas','popu',NULL,'S','1',NULL,800,600),('10B','Cajas','Cajas','SUPERMERCADO','supermercado/cajas','popu',NULL,'S','1',NULL,800,600),('10C','Ventas por dia de la semana','Ventas por dia de la semana','CONSULTAS','supermercado/dias','popu',NULL,'S','1',NULL,800,600),('10D','Ventas Clasificadas','Ventas Clasificadas','CONSULTAS','supermercado/tventas','popu',NULL,'S','1',NULL,800,600),('10E','Tipo de Pagos','Tipo de Pagos','CONSULTAS','supermercado/tpagos','popu',NULL,'S','1',NULL,800,600),('90C','Gestión de Formatos','Formatos','SOPORTE','/supervisor/formatos','popu',NULL,'S','9',NULL,1024,600),('119','Precio en Cajas','Consulta de precio en cajas','PUNTO DE VENTA','supermercado/consulcajas','popu',NULL,'S','1',NULL,800,600),('11A','Cierre Z','Cierre Z','PUNTO DE VENTA','ventas/fiscalz','popu',NULL,'S','1',NULL,800,600),('90D','Sucursales','Sucursales','CONFIGURACION','supervisor/sucu','popu',NULL,'S','9',NULL,800,600),('700','Profesiones','Profesiones','REGISTROS','nomina/prof','popu',NULL,'S','7',NULL,800,600),('90F','Bitacora','Bitacora','OTROS','supervisor/bitacora','popu',NULL,'S','9',NULL,800,600),('11B','Arqueo de Caja','Arqueo de Caja','PUNTO DE VENTA','ventas/dine','popu',NULL,'S','1',NULL,800,600),('910','Acceso Datasis','Acceso Datasis','CONFIGURACION','supervisor/acdatasis','popu',NULL,'S','9',NULL,800,600),('911','Directorio','Informacion sobre clientes','OTROS','supervisor/directorio','popu',NULL,'S','9',NULL,800,600),('9110','Agregar Cliente','Agregar Cliente','OTROS','ventas/cliente/dataedit/create','self',NULL,'S','911',NULL,800,600),('9111','Agregar Proveedor','Agregar Proveedor','OTROS','compras/proveed/dataedit/create','self',NULL,'S','911',NULL,800,600),('9112','Agregar Empleado','Agregar Empleado','OTROS','nomina/personal/dataedit/create','self',NULL,'S','911',NULL,800,600),('912','Reportes de Trabajo','Reportes de Trabajo','SOPORTE','supervisor/repotra','popu',NULL,'S','9',NULL,800,600),('9120','Imprimir','Imprimir','OTROS','formatos/ver/repotra/<#5#>','popu',NULL,'S','912',NULL,800,600),('913','Informacion de Conexion','Conexion con Clientes','SOPORTE','supervisor/conec','popu',NULL,'S','9',NULL,800,600),('914','Tickets de Clientes','Tickets de Clientes ','SOPORTE','supervisor/tiketc','popu',NULL,'S','9',NULL,800,600),('915','Servicio Tecnico','Servicio Tecnico','SOPORTE','supervisor/tiketservi','popu',NULL,'S','9',NULL,800,600),('30A','Consulta de Precios','Consulta de Precios','CONSULTAS','/inventario/consultas','popu',NULL,'S','3',NULL,800,600),('30D','Inventario Express','Inventario Express','SUPERMERCADO','supermercado/efisico','popu',NULL,'S','3',NULL,800,600),('50C','Análisis de Mov. de Ban','Análisis de Mov. de Bancos','CONSULTAS','/finanzas/analisisbanc','popu',NULL,'S','5',NULL,800,600),('917','Imprimir ticket','Imprimir ticket','SOPORTE','supervisor/tiketimp/imprimir','popu',NULL,'S','9',NULL,800,600),('50D','Análisis de Gastos','Análisis de Gastos','CONSULTAS','/finanzas/analisisgastos','popu',NULL,'S','5',NULL,800,600),('50E','Visión General','Visión General','CONSULTAS','/finanzas/analisisvision','popu',NULL,'S','5',NULL,800,600),('1041','Descargar .xls','Haz clic para Descargar El Presupuesto en Formato .xls','TRANSACCIONES','/ventas/xlspresupuesto/ver/<#5#>','self',NULL,'S','104',NULL,800,600),('30B','Marcas','Gestion de marcas','REGISTROS','/inventario/marc','popu',NULL,'S','3',NULL,800,600),('30C','Unidades','Gestion de Unidades','REGISTROS','/inventario/unidad','popu',NULL,'S','3',NULL,800,600),('918','Analisis de Reportes','Análisis De Reportes','OTROS','supervisor/repodupli','popu',NULL,'S','9',NULL,800,600),('11C','Clientes en cajas','Clientes en cajas','PUNTO DE VENTA','supermercado/consulcajas/club','popu',NULL,'S','1',NULL,800,600),('11D','Precios en Sucursales','Precios en Sucursales','SUPERMERCADO','supermercado/consulsucu/precios','popu',NULL,'S','1',NULL,800,600),('11E','Apagar Cajas','Apagar Cajas','PUNTO DE VENTA','/supermercado/apagar','popu',NULL,'S','1',NULL,800,600),('30E','Reportes de Supermercado','Reportes de Supermercado','REPORTES','reportes/index/maes','popu',NULL,'S','3',NULL,800,600),('50F','Vision General','Vision General de Supermercado','CONSULTAS','supermercado/analisisvision','popu',NULL,'S','5',NULL,800,600),('30F','Familias de Inventario','Familias de Inventario','SUPERMERCADO','/supermercado/fami','popu',NULL,'S','3',NULL,800,600),('916','Control de acceso a internet','Control de acceso a internet','CONFIGURACION','/supervisor/internet','popu',NULL,'S','9',NULL,800,600),('11F','Conciliación Z','Conciliación de cierres Z','SUPERMERCADO','supermercado/conciliacion','popu',NULL,'S','1',NULL,800,600),('126','Ventas por  Existencia','Ventas por  Existencia','CONSULTAS','inventario/gproductos','popu',NULL,'S','1',NULL,800,600),('5150','Libro de Retetxt','Libro de Retetxt','OBLIGACIONES','finanzas/retetxt','popu',NULL,'S','515',NULL,800,600),('51A','Reportes De Retenciones','Reportes De Retenciones','REPORTES','/reportes/index/riva','popu',NULL,'S','5',NULL,800,600),('51B','Conformación cheques','Conformación cheques','BANCOS','/finanzas/conforch','popu',NULL,'S','5',NULL,800,600),('314','Catalogo','Ver catalogo','CONSULTAS','/inventario/catalogover','popu',NULL,'S','3',NULL,800,600),('315','Conversiones','Conversiones','TRANSACCIONES','inventario/conversiones','popu',NULL,'S','3',NULL,800,600),('127','Exportar Ventas','Exportar Ventas','SUPERMERCADO','/supermercado/exportar/exventas','popu',NULL,'S','1',NULL,800,600),('919','Cargar Data Exportada SQL','Cargar data exportada en las sucursales','UNICO','/cargasarch/cargasql','popu',NULL,'S','9',NULL,800,600),('316','Exportar Inventario','Exportar Inventario','SUPERMERCADO','supermercado/exportar/exmaes','popu',NULL,'S','3',NULL,800,600),('91A','Publicidad','Publicidad','CONFIGURACION','/supervisor/publicidad','popu',NULL,'S','9',NULL,800,600),('318','Reporte de Transferencias','Reporte de Transferencias','REPORTES','reportes/index/stra','popu',NULL,'S','3',NULL,800,600),('51C','Resumen Diario','Resumen Diario','CONSULTAS','finanzas/resumendiario','popu',NULL,'S','5',NULL,800,600),('12A','Recepción de caja','Recepción de caja','PUNTO DE VENTA','/ventas/rcaj/','popu',NULL,'S','1',NULL,800,600),('91B','Menu de Datasis','tmenus','CONFIGURACION','supervisor/tmenus','popu',NULL,'S','9',NULL,800,600),('91C','Carga de  Vendedor','Carga de  Vendedor','UNICO','cargasarch/vendambul','popu',NULL,'S','9',NULL,800,600),('91D','Exportar Data','Exportar Data','EXPORT/IMPORT','sincro/exportar/uig','popu',NULL,'S','9',NULL,800,600),('91E','Importar Data','Importar Data','EXPORT/IMPORT','sincro/importar/uitraeg','popu',NULL,'S','9',NULL,800,600),('91F','Cargar Archivo','Cargar Archivo','EXPORT/IMPORT','sincro/importar/uicarga','popu',NULL,'S','9',NULL,800,600),('12B','Metas Propuestas','Metas Propuestas','REGISTROS','/ventas/metas','popu',NULL,'S','1',NULL,800,600),('12C','Comisiones','Comisiones de vendedores','TRANSACCIONES','ventas/sfacpaga','popu',NULL,'S','1',NULL,800,600),('70A','Horarios del Personal','Horarios del Personal','REGISTROS','nomina/horarios','popu',NULL,'S','7',NULL,800,600),('921','B2B','Comunicacion entre empresas asociadas','EXPORT/IMPORT','sincro/b2b/index','popu',NULL,'S','9',NULL,800,600),('319','Inventario Fisico','Inventario Fisico','TRANSACCIONES','inventario/invfis','popu',NULL,'S','3',NULL,800,600),('12D','Enviar Caja','Enviar Caja','SUPERMERCADO','supermercado/enviacaja','popu',NULL,'S','1',NULL,800,600),('600','Conceptos Contables','Otros Conceptos contables','REGISTROS','finanzas/botr','popu',NULL,'S','6',NULL,800,600),('31A','Inventario','Inventario','SUPERMERCADO','supermercado/maes','popu',NULL,'S','3',NULL,800,600),('20B','Aumento','Aumento','FARMACIA','farmacia/aumento','popu',NULL,'S','2',NULL,800,600),('920','Importar data paramétrico','Importar data parametrico','EXPORT/IMPORT','sincro/importar/uitraepara','popu',NULL,'S','9',NULL,800,600),('31B','Adicionar Código de barras','','REGISTROS','/inventario/barraspos','popu',NULL,'S','3',NULL,800,600),('31C','Etiquetas','','REPORTES','/inventario/etiqueta_sinv/','popu',NULL,'S','3',NULL,800,600),('70B','Definición de Utilidades','Definición de Utilidades','REGISTROS','nomina/notabu','popu',NULL,'S','7',NULL,800,600),('4','Productividad','crm','','','self',NULL,'S','',NULL,800,600),('400','Contenedor','','CRM','/crm/contenedor','popu',NULL,'S','4',NULL,800,600),('401','Estatus','Estatus de contenedores','CRM','crm/status','popu',NULL,'S','4',NULL,800,600),('402','Tipos','Tipos de contenedores','CRM','crm/tipos','popu',NULL,'S','4',NULL,800,600),('403','Definiciones','Definiciones de contenedores','CRM','crm/definiciones','popu',NULL,'S','4',NULL,800,600),('31D','Promocion de inventario','Promocion','REGISTROS','/inventario/sinvpromo','popu',NULL,'S','3',NULL,800,600),('20C','Asignaciones de productos','Asignaciones de productos provenientes de las droguerias','FARMACIA','farmacia/scst/asignarfiltro','popu',NULL,'S','2',NULL,800,600),('9210','Compras traidas','Ver las compras traidas de proveedores','','sincro/b2b/scstfilter','self',NULL,'S','921',NULL,800,600),('12E','Retiros de Caja','Retiros de dinero en caja','PUNTO DE VENTA','ventas/rret','popu',NULL,'S','1',NULL,800,600),('51D','Movimientos de caja','Movimientos de caja','BANCOS','/finanzas/bcaj/','popu',NULL,'S','5',NULL,800,600),('31E','Otras Opciones de Inventario','Otras Opciones de Inventario','SUPERMERCADO','/supermercado/actlocali','popu',NULL,'S','3',NULL,800,600),('51D0','Conciliación de cierres','Conciliación de cierres','BANCOS','/finanzas/bcaj/autotranfer','self',NULL,'S','51D',NULL,800,600),('922','Monitoreo','Monitoreo','UNICO','supervisor/monitoreo','popu',NULL,'S','9',NULL,800,600),('31F','Cambio de precios','Cambio de precios rapidamente','TRANSACCIONES','inventario/precios_sinv','popu',NULL,'S','3',NULL,400,200);
/*!40000 ALTER TABLE `intramenu` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-01-28 10:45:49
