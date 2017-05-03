
-- Ajuste --

ALTER TABLE `CB04_EMPRESA` ADD FOREIGN KEY (`CB04_CATEGORIA_ID`) REFERENCES `cashback`.`CB10_CATEGORIA`(`CB10_ID`) ON DELETE RESTRICT ON UPDATE NO ACTION;
ALTER TABLE `CB07_CASH_BACK` ADD PRIMARY KEY(`CB07_ID`);
ALTER TABLE `CB07_CASH_BACK` CHANGE `CB07_ID` `CB07_ID` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `CB07_CASH_BACK` CHANGE `CB07_PERCENTUAL` `CB07_PERCENTUAL` DECIMAL(10,2) NOT NULL;
ALTER TABLE `CB00_TRANSFERENCIA` ADD `CB00_COD_BANCO` INT NOT NULL AFTER `CB00_DT_CONCLUSAO`, ADD `CB00_TP_CONTA` INT NOT NULL AFTER `CB00_COD_BANCO`, ADD `CB00_NUM_CONTA` INT NOT NULL AFTER `CB00_TP_CONTA`, ADD `CB00_AGENCIA` VARCHAR(5) NOT NULL AFTER `CB00_NUM_CONTA`;
ALTER TABLE `CB00_TRANSFERENCIA` CHANGE `CB00_ID` `CB00_ID` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `CB04_EMPRESA` ADD `CB04_URL_LOGOMARCA` VARCHAR(100) NULL DEFAULT NULL AFTER `CB04_OBSERVACAO`;
ALTER TABLE `CB05_PRODUTO` ADD `CB05_NOME_CURTO` VARCHAR(15) NOT NULL AFTER `CB05_EMPRESA_ID`;
ALTER TABLE `CB05_PRODUTO` ADD `CB05_IMPORTANTE` TEXT NULL DEFAULT NULL AFTER `CB05_DESCRICAO`;


-- VIEW
CREATE
ALGORITHM = UNDEFINED
SQL SECURITY INVOKER
VIEW `VIEW_EXTRATO_CLIENTE`
AS SELECT CB01_TRANSACAO.CB01_ID AS TRANSACAO_ID,
'NULL' AS TRANSFERENCIA_ID,
'TRANSAÇÃO' AS TIPO,
CB04_EMPRESA.CB04_ID AS EMPRESA_ID,
CB04_EMPRESA.CB04_NOME AS EMPRESA_NM, 
CB01_TRANSACAO.CB01_DT_COMPRA AS DT1,		
'NULL' AS DT2,
CB01_TRANSACAO.CB01_VALOR_COMPRA AS VLR1, 	
CB01_TRANSACAO.CB01_VALOR_DEVOLTA AS VLR2, 	
CB01_TRANSACAO.CB01_STATUS AS STATUS,
CB01_TRANSACAO.CB01_CLIENTE_ID AS CLIENTE
FROM CB01_TRANSACAO
INNER JOIN CB04_EMPRESA ON(CB04_EMPRESA.CB04_ID=CB01_TRANSACAO.CB01_EMPRESA_ID)

UNION

SELECT 'NULL' AS TRANSACAO_ID,
CB00_TRANSFERENCIA.CB00_ID AS TRANSFERENCIA_ID,
'TRANSFERÊNCIA' AS TIPO,
'NULL' AS EMPRESA_ID,
'NULL' AS EMPRESA_NM, 
CB00_TRANSFERENCIA.CB00_DT_SOLICITACAO AS DT1,
CB00_TRANSFERENCIA.CB00_DT_CONCLUSAO AS DT2,
CB00_TRANSFERENCIA.CB00_VALOR_TRANSFERIDO AS VLR1,	
(CB00_TRANSFERENCIA.CB00_VALOR_TRANSFERIDO * (-1)) AS VLR2,
CB00_TRANSFERENCIA.CB00_STATUS AS STATUS,
CB00_TRANSFERENCIA.CB00_CLIENTE_ID AS CLIENTE
FROM CB00_TRANSFERENCIA

ORDER BY DT1 DESC





---------------------	DADOS PARA TESTE ----------------------
-- add usuario
INSERT INTO `CB02_CLIENTE` (`CB02_ID`, `CB02_NOME`, `CB02_CPF`, `CB02_EMAIL`, `CB02_STATUS`, `CB02_DT_CADASTRO`) VALUES (NULL, 'Eduardo Matias', '535.334.688-28', 'emailteste@test.com', '1', CURRENT_TIMESTAMP);

-- add categorias
INSERT INTO `CB10_CATEGORIA` (`CB10_ID`, `CB10_NOME`, `CB10_STATUS`) VALUES (NULL, 'Motel', '1'), (NULL, 'Hotel', '1');

-- add empresa
INSERT INTO `CB04_EMPRESA` (`CB04_ID`, `CB04_NOME`, `CB04_CATEGORIA_ID`, `CB04_FUNCIONAMENTO`, `CB04_OBSERVACAO`, `CB04_STATUS`, `CB04_QTD_FAVORITO`, `CB04_QTD_COMPARTILHADO`, `CB04_END_LOGRADOURO`, `CB04_END_BAIRRO`, `CB04_END_CIDADE`, `CB04_END_UF`, `CB04_END_NUMERO`, `CB04_END_COMPLEMENTO`, `CB04_END_CEP`) VALUES (NULL, 'Empresa teste', '1', 'Sobre o funcionamento da empresa...', 'Observações...', '1', '4', '3', 'Av. avenida teste', 'Centro', 'Contagem', 'MG', 'S/N', 'Ao lado do posto de gasolina', '32010200');

-- add transacoes
INSERT INTO `CB01_TRANSACAO` (`CB01_ID`, `CB01_CLIENTE_ID`, `CB01_EMPRESA_ID`, `CB01_DT_COMPRA`, `CB01_STATUS`, `CB01_VALOR_COMPRA`, `CB01_VALOR_DEVOLTA`) VALUES (NULL, '1', '1', CURRENT_TIMESTAMP, '1', '100.00', '20.00'), (NULL, '1', '1', '2017-03-15 12:47:10', '1', '350', '70.00');

-- add produto
INSERT INTO `CB05_PRODUTO` (`CB05_ID`, `CB05_EMPRESA_ID`, `CB05_TITULO`, `CB05_DESCRICAO`) VALUES (NULL, '1', 'Produto de teste', 'Exemplo de descrição para o produto de teste da empresa de teste');

-- add forma de pagamento
INSERT INTO `CB08_FORMA_PAGAMENTO` (`CB08_ID`, `CB08_NOME`, `CB08_URL_IMG`, `CB08_STATUS`) VALUES (NULL, 'Meu Checkout', '', '1'), (NULL, 'Visa', '', '1');

-- add forma de pagamento para a empresa
INSERT INTO `CB09_FORMA_PAG_EMPRESA` (`CB09_EMPRESA_ID`, `CB09_FORMA_PAG_ID`) VALUES ('1', '1'), ('1', '2');

-- add item da categoria 
INSERT INTO `CB11_ITEM_CATEGORIA` (`CB11_ID`, `CB11_CATEGORIA_ID`, `CB11_DESCRICAO`, `CB11_STATUS`) VALUES (NULL, '1', 'Item 1 da cat 1', '1'), (NULL, '1', 'Item 2 da cat 1', '1');

-- add vinculo do item da categoria no produto
INSERT INTO `CB12_ITEM_CATEG_EMPRESA` (`CB12_ID`, `CB12_ITEM_ID`, `CB12_EMPRESA_ID`, `CB12_PRODUTO_ID`) VALUES (NULL, '1', NULL, '1'), (NULL, '2', NULL, '1');

-- add transferencia
INSERT INTO `CB00_TRANSFERENCIA` (`CB00_ID`, `CB00_CLIENTE_ID`, `CB00_DT_SOLICITACAO`, `CB00_DT_CONCLUSAO`, `CB00_COD_BANCO`, `CB00_TP_CONTA`, `CB00_NUM_CONTA`, `CB00_AGENCIA`, `CB00_STATUS`, `CB00_VALOR_TRANSFERIDO`) VALUES ('', '1', '2017-03-18 12:25:36', NULL, '123', '4', '12344323', '532', '2', '100');

-- add logo marca no estabelecimento 1
UPDATE `CB04_EMPRESA` SET `CB04_URL_LOGOMARCA` = 'https://www.guiademoteis.com.br/imagens/logotipos/116-forest-hills.gif' WHERE `CB04_EMPRESA`.`CB04_ID` = 1;

-- add imagem do estabelecimento
INSERT INTO `CB13_FOTO_EMPRESA` (`CB13_ID`, `CB13_EMPRESA_ID`, `CB13_CAMPA`, `CB13_URL`) VALUES (NULL, '1', '1', 'img/motel-teste/Motel-Baton2109-fachada.jpg');
INSERT INTO `CB13_FOTO_EMPRESA` (`CB13_ID`, `CB13_EMPRESA_ID`, `CB13_CAMPA`, `CB13_URL`) VALUES (NULL, '1', '0', 'img/demo/m3.jpg');

-- produto
UPDATE `CB05_PRODUTO` SET `CB05_NOME_CURTO` = 'Master', `CB05_TITULO` = 'Suíte Master', `CB05_DESCRICAO` = 'As características incluem uma tranquila área de estar, colchões de penas de luxo, roupa de cama egípcia de qualidade superior, controle individual do clima, Televisões Interativas IP e sistema à prova de som de última geração.' WHERE `CB05_PRODUTO`.`CB05_ID` = 1;

-- add produto
INSERT INTO `CB05_PRODUTO` (`CB05_ID`, `CB05_EMPRESA_ID`, `CB05_NOME_CURTO`, `CB05_TITULO`, `CB05_DESCRICAO`) VALUES (NULL, '1', 'Luxo', 'Suíte Luxo', 'Dispõem de muita luz natural graças às janelas que vão do piso ao teto. Também oferecem uma área cômoda de salão. Os banheiros de estilo contemporâneo dispõem de uma generosa ducha balinesa e comodidades de categoria superior. ');

-- imagens do produto 1, 2 e 3
INSERT INTO `CB14_FOTO_PRODUTO` (`CB14_ID`, `CB14_PRODUTO_ID`, `CB14_CAPA`, `CB14_URL`) VALUES (NULL, '1', '1', 'img/motel-teste/116_big_4091_4.jpg'), (NULL, '1', '0', '116_big_553_1.jpg');
INSERT INTO `CB14_FOTO_PRODUTO` (`CB14_ID`, `CB14_PRODUTO_ID`, `CB14_CAPA`, `CB14_URL`) VALUES (NULL, '2', '1', 'img/motel-teste/116_big_2022_2.jpg'), (NULL, '2', '0', 'img/motel-teste/116_big_2022_3.jpg'), (NULL, '2', '0', 'img/motel-teste/116_big_2022_4.jpg');
UPDATE `CB14_FOTO_PRODUTO` SET `CB14_URL` = 'img/motel-teste/116_big_553_1.jpg' WHERE `CB14_FOTO_PRODUTO`.`CB14_ID` = 2;
INSERT INTO `CB14_FOTO_PRODUTO` (`CB14_ID`, `CB14_PRODUTO_ID`, `CB14_CAPA`, `CB14_URL`) VALUES (NULL, '3', '1', 'img/motel-teste/116_big_552_2.jpg'), (NULL, '3', '0', 'img/motel-teste/116_big_552_1.jpg');
INSERT INTO `CB14_FOTO_PRODUTO` (`CB14_ID`, `CB14_PRODUTO_ID`, `CB14_CAPA`, `CB14_URL`) VALUES (NULL, '3', '0', 'img/motel-teste/116_big_552_3.jpg');

-- add valor ao campo importante no produto 1
UPDATE `CB05_PRODUTO` SET `CB05_IMPORTANTE` = 'Pernoite Antecipado! Aproveite o pernoite com entrada as 18h e saída as 14h. Válido às sextas, sábados, feriados e vésperas. * Preços válidos para 2 pessoas. » Hora adicional - R$ 11,00. » Em feriados, vésperas, Dia dos Namorados e Reveillon será cobrado o valor do fim de semana. Os itens de decoração apresentados nas fotos, estão disponíveis no cardápio do motel.' WHERE `CB05_PRODUTO`.`CB05_ID` = 1;

-- add variações do produto 1
INSERT INTO `CB06_VARIACAO` (`CB06_ID`, `CB06_PRODUTO_ID`, `CB06_DESCRICAO`, `CB06_PRECO`) VALUES (NULL, '1', '2ª a 6ª - 1h', '60'), (NULL, '1', '2ª a 6ª - 2h', '70'), (NULL, '1', '2ª a 6ª - 3h', '80'), (NULL, '1', 'Pernoite: de 21h até as 14h', '130');

-- add produtos
INSERT INTO `CB05_PRODUTO` (`CB05_ID`, `CB05_EMPRESA_ID`, `CB05_NOME_CURTO`, `CB05_TITULO`, `CB05_DESCRICAO`, `CB05_IMPORTANTE`) VALUES (NULL, '1', 'Super Luxo', 'Apto Super Luxo', NULL, NULL), (NULL, '1', 'Com Piscina', 'Suíte com Piscina', NULL, NULL);

UPDATE `CB11_ITEM_CATEGORIA` SET `CB11_DESCRICAO` = 'hidro' WHERE `CB11_ITEM_CATEGORIA`.`CB11_ID` = 2; UPDATE `CB11_ITEM_CATEGORIA` SET `CB11_DESCRICAO` = 'frigobar' WHERE `CB11_ITEM_CATEGORIA`.`CB11_ID` = 1;
INSERT INTO `CB11_ITEM_CATEGORIA` (`CB11_ID`, `CB11_CATEGORIA_ID`, `CB11_DESCRICAO`, `CB11_STATUS`) VALUES (NULL, '1', 'ar-condicionado', '1'), (NULL, '1', 'ducha', '1'), (NULL, '1', 'canal erótico', '1'), (NULL, '1', 'piscina', '1');

-- add variacao
INSERT INTO `CB06_VARIACAO` (`CB06_ID`, `CB06_PRODUTO_ID`, `CB06_DESCRICAO`, `CB06_PRECO`) VALUES (NULL, '3', 'Variação 1', '45'), (NULL, '3', 'Variação 2', '50');

-- add cashback na variacao
INSERT INTO `CB07_CASH_BACK` (`CB07_ID`, `CB07_PRODUTO_ID`, `CB07_VARIACAO_ID`, `CB07_DIA_SEMANA`, `CB07_PERCENTUAL`) VALUES (NULL, NULL, '6', '2', '10'), (NULL, NULL, '6', '3', '15'), (NULL, NULL, '6', '4', '20'), (NULL, NULL, '7', '0', '5'), (NULL, NULL, '7', '1', '45');

-- add item da categoria no produto
INSERT INTO `CB12_ITEM_CATEG_EMPRESA` (`CB12_ID`, `CB12_ITEM_ID`, `CB12_EMPRESA_ID`, `CB12_PRODUTO_ID`) VALUES (NULL, '3', NULL, '2'), (NULL, '5', NULL, '2');

-- add novo motel
INSERT INTO `CB04_EMPRESA` (`CB04_ID`, `CB04_NOME`, `CB04_CATEGORIA_ID`, `CB04_FUNCIONAMENTO`, `CB04_OBSERVACAO`, `CB04_URL_LOGOMARCA`, `CB04_STATUS`, `CB04_QTD_FAVORITO`, `CB04_QTD_COMPARTILHADO`, `CB04_END_LOGRADOURO`, `CB04_END_BAIRRO`, `CB04_END_CIDADE`, `CB04_END_UF`, `CB04_END_NUMERO`, `CB04_END_COMPLEMENTO`, `CB04_END_CEP`) VALUES (NULL, 'Motel Fantasy III', '1', 'O Motel oferece quatro categorias de suíte a seus hóspedes, ambas equipadas com ar-condicionado, TV com canal erótico, garagem privativa, som e ducha. A categoria Super Luxo conta com hidro anatômica, TV LCD 40'''' e internet Wi-Fi. Já a categoria Temática conta ainda com uma decoração personalizada. Aproveite o café da manhã, oferecido como cortesia aos pernoites, e programe sua visita!', '', 'img/motel-teste/Motel-Fantasy-III-logo-grande.jpg', '1', '56', '23', 'Rodovia Anel Rodoviário Celso Mello Azevedo', 'Caiçaras', 'Belo Horizonte', 'MG', ' 1630', 'BR-262', '81736132');
INSERT INTO `CB13_FOTO_EMPRESA` (`CB13_ID`, `CB13_EMPRESA_ID`, `CB13_CAMPA`, `CB13_URL`) VALUES (NULL, '2', '1', 'img/motel-teste/foto1-fachada.gif');
INSERT INTO `CB05_PRODUTO` (`CB05_ID`, `CB05_EMPRESA_ID`, `CB05_NOME_CURTO`, `CB05_TITULO`, `CB05_DESCRICAO`, `CB05_IMPORTANTE`) VALUES (NULL, '2', 'Super Luxo', 'Suíte Super Luxo', NULL, 'Promoção - Super Pernoite! Vá ao motel, hospeda-se para o pernoite e ganhe: Refeição* + Café da Manhã! Válido todos os dias, em todas as suítes. *Refeição: Almoço ou Jantar. » Não acumulativo com demais promoções e cortesias oferecidas pelo Motel. » Opção de suíte com pole dance.');
INSERT INTO `CB07_CASH_BACK` (`CB07_ID`, `CB07_PRODUTO_ID`, `CB07_VARIACAO_ID`, `CB07_DIA_SEMANA`, `CB07_PERCENTUAL`) VALUES (NULL, '4', NULL, NULL, '15');
INSERT INTO `CB12_ITEM_CATEG_EMPRESA` (`CB12_ID`, `CB12_ITEM_ID`, `CB12_EMPRESA_ID`, `CB12_PRODUTO_ID`) VALUES (NULL, '3', NULL, '4');


-----------------------------------------------------------------






-- VIEW CHARLAN
select `cashback`.`CB06_VARIACAO`.`CB06_ID` AS `CB06_ID`,
`cashback`.`CB05_PRODUTO`.`CB05_ID` AS `CB05_ID`,
`cashback`.`CB06_VARIACAO`.`CB06_TITULO` AS `CB06_TITULO`,
`cashback`.`CB06_VARIACAO`.`CB06_DESCRICAO` AS `CB06_DESCRICAO`,
`cashback`.`CB06_VARIACAO`.`CB06_PRECO` AS `CB06_PRECO`,
`cashback`.`CB07_CASH_BACK`.`CB07_PERCENTUAL` AS `CB07_PERCENTUAL`,((`cashback`.`CB07_CASH_BACK`.`CB07_PERCENTUAL` / 100) * `cashback`.`CB06_VARIACAO`.`CB06_PRECO`) AS `VALOR_CB` 
from ((`cashback`.`CB05_PRODUTO` 
join `cashback`.`CB06_VARIACAO` on((`cashback`.`CB06_VARIACAO`.`CB06_PRODUTO_ID` = `cashback`.`CB05_PRODUTO`.`CB05_ID`))) 
join `cashback`.`CB07_CASH_BACK` on((`cashback`.`CB07_CASH_BACK`.`CB07_VARIACAO_ID` = `cashback`.`CB06_VARIACAO`.`CB06_ID`))) 
group by `cashback`.`CB06_VARIACAO`.`CB06_ID`,`cashback`.`CB05_PRODUTO`.`CB05_ID`, `cashback`.`CB06_VARIACAO`.`CB06_TITULO`,`cashback`.`CB06_VARIACAO`.`CB06_DESCRICAO`, `cashback`.`CB06_VARIACAO`.`CB06_PRECO`,`cashback`.`CB07_CASH_BACK`.`CB07_PERCENTUAL` 
order by `cashback`.`CB07_CASH_BACK`.`CB07_PERCENTUAL` desc
