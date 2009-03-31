#!/usr/bin/env python
#-*- encoding: utf-8 -*-
#
#       actualiza.py
#       
#       Rui Teixeira <ruijst@gmail.com>
#       
#       This program is free software; you can redistribute it and/or modify
#       it under the terms of the GNU General Public License as published by
#       the Free Software Foundation; either version 2 of the License, or
#       (at your option) any later version.
#       
#       This program is distributed in the hope that it will be useful,
#       but WITHOUT ANY WARRANTY; without even the implied warranty of
#       MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#       GNU General Public License for more details.
#       
#       You should have received a copy of the GNU General Public License
#       along with this program; if not, write to the Free Software
#       Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
#       MA 02110-1301, USA.

import urllib2
import urllib
import re
import time
import sys, getopt


def main():
	# inicializa algumas variáveis
	web_useragent = "Mozilla/5.0 (X11; U; Linux i686; pt-PT; rv:1.9.0.5) Gecko/2008121622 Ubuntu/8.10 (intrepid) Firefox/3.0.5"
	headers = {'User-agent' : web_useragent}
	esc = Escaper("\\'")
	
	url = "http://www.base.gov.pt/_layouts/ccp/AjusteDirecto/List.aspx"
	req = urllib2.Request(url, None, headers)
	acedido = False
	while (not acedido):
		try:
			handle = urllib2.urlopen(req)
		        acedido = True
		except URLerror:
			pass
	data = handle.read()
	handle.close()
	numero_paginas = int(re.findall('id="ctl00_PlaceHolderMain_hddnPageLast" value="(.*?)"', data)[0])
	#numero_paginas = 1
	
	# procura os inputs para poder mudar de pagina
	form = re.findall('<form name="aspnetForm" method="post" action="List.aspx" id="aspnetForm">(.*?)</form>', data, re.DOTALL)[0]
	inputs = re.findall('<input(.*?)>', form)
	postparams = {}
	for input in inputs:
		try:
			postparams[re.findall('name="(.*?)"', input)[0]] = re.findall('value="(.*?)"', input)[0]
		except:
			pass
		postparams['ctl00$PlaceHolderMain$Img3.x'] = "6"
		postparams['ctl00$PlaceHolderMain$Img3.y'] = "7"
		
	# procura nas paginas por novos ADs
	# numero_paginas = 10
	ads = []
	for p in range(0, numero_paginas):
		sys.stderr.write("Analizando página %d de %d (%d ADs)\r"%(p+1, numero_paginas, len(ads)))
		sys.stderr.flush()
		
		postparams['ctl00$PlaceHolderMain$hddnPage'] = p
		erro = True
		tentativas = 0
		try:
			query = urllib.urlencode(postparams)
			req = urllib2.Request(url, query, headers)
			acedido = False
			while (not acedido):
				try:
					handle = urllib2.urlopen(req)
					acedido = True
				except URLerror:
					pass
			data = handle.read()
			handle.close()
			listaads = re.findall('\'Detail.aspx\?idAjusteDirecto=(\d+)\' .*?\>(.*?)\<', data)
			for ad in listaads:
				ads.append(ad[0])
		except:
			sys.stderr.write( "\nFalha na pagina %d (tentativa %d)\n"%(p,tentativas))
			tentativas = tentativas+1
			if tentativas == 6:
				sys.stderr.write( "Erro na análise das páginas. Tente mais tarde")
				exit(1)
	for ad in ads:
	    print ad
	exit(0)

class Escaper(object):
	def __init__(self, chars):
		self.pat = re.compile("[%s]" % re.escape(chars))
	def __call__(self, data):
		return self.pat.sub(lambda c: "\\"+c.group(0), data)


if __name__ == '__main__': main()
