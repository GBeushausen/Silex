import sys, os
from sphinx.highlighting import lexers
from pygments.lexers.web import PhpLexer

sys.path.append(os.path.abspath('_exts'))

extensions = []
master_doc = 'index'
highlight_language = 'php'

project = u'PrestoPHP'
copyright = u'2010-2021 Fabien Potencier, Gunnar Beushausen'
html_theme = "bizstyle"

version = '2.4'
release = '2.4.2'

lexers['php'] = PhpLexer(startinline=True)
