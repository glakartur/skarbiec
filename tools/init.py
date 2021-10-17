#!/usr/bin/env python
# -*- coding: utf-8 -*-

import os
from tools.cfg import Cfg

class Init:
    def __init__(self):
        self.config = Cfg()
    def process(self):
        if (os.path.exists(self.config.fileName)):
            print("Skarbiec is already initialized.")
            return
        print("Initializing...");
        if (not os.path.exists(self.config.transactionsFolder)):
            print("# creating folder for account transactions:", self.config.transactionsFolder);
            os.mkdir(self.config.transactionsFolder)
        if (not os.path.exists(self.config.dataFolder)):
            print("# creating folder for data:", self.config.dataFolder);
            os.mkdir(self.config.dataFolder)
        print("# setting folder as Skarbiec data source");
        with open(self.config.fileName, "w+"):
            pass
        print("done.");

if __name__ == "__main__":
    pass