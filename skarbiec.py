#!/usr/bin/env python
# -*- coding: utf-8 -*-

import os
import sys
import json
from tools.init import Init
from tools.cfg import Cfg
from tools.transactionImporter import TransactionImporter

class import_transactions:
    def __init__(self):
        self.config = Cfg()
    def process(self):
        print()
        print("----------------")
        print("   Skarbiec")
        print("----------------")
        print()
        for eachArg in sys.argv[1:]:
            print(eachArg)
            if eachArg == "init":
                init = Init()
                init.process()
                return
            
            if (not os.path.exists(self.config.fileName)):
                print("Skarbiec is not initialized. Run `skarbiec.py init`")
                return

            if eachArg == "import-transactions":
                transactionImporter = TransactionImporter()
                transactionImporter.process()
                return

        print("Usage:")
        print("skarbiec.py ACTION")
        print()
        print("ACTIONS:")
        print("  init - initialization")
        print("  import-transactions - transactions update")
        print()

if __name__ == "__main__":
    import_transactions().process()