#!/usr/bin/python3
# -*- coding: utf-8 -*-
"""
Created on Nov 29, 2019
Desc: Webp convertor QT
@author: Mashiro @ https://2heng.xin
"""

import sys
import time
from PyQt5.QtWidgets import QMainWindow, QWidget, QApplication, QPushButton, QMessageBox, QDesktopWidget, QGridLayout
from PyQt5.QtCore import QCoreApplication
from PyQt5.QtGui import QIcon
from manifest import main as manifest

class MainWindow(QMainWindow):
  def __init__(self):
    super().__init__()
    self.initUI()

  def initUI(self):
    self.statusBar().showMessage('Ready')

    self.BtnWid = QWidget(self)
    self.setCentralWidget(self.BtnWid)
    grid = QGridLayout()
    self.BtnWid.setLayout(grid)

    names = ['Generate manifest.json',
             'Pull from GitHub',
             'Push to GitHub',
             'Release on GitHub',
             'Push manifest.json to WordPress',
             'About and Turtor']

    actions = [self.Action_1,
               self.Action_0,
               self.Action_0,
               self.Action_0,
               self.Action_0]

    positions = [(i, j) for i in range(6) for j in range(1)]

    for position, name, action in zip(positions, names, actions):
      if name == '':
        continue
      button = QPushButton(name)
      button.clicked.connect(action)
      grid.addWidget(button, *position)

    # self.resize(500, 500)
    self.center()
    self.setWindowTitle('Manifest Generator')
    self.setWindowIcon(QIcon('icon.png'))

    self.show()

  def Action_0(self):
    sender = self.sender()
    self.statusBar().showMessage('"' + sender.text() + '" was pressed')

  #Generate manifest.json
  def Action_1(self):
    # self.statusBar().showMessage('Processing...')
    # time.sleep(1)
    manifest()
    self.statusBar().showMessage('`manifest.json` saved.')

  def center(self):
    qr = self.frameGeometry()
    cp = QDesktopWidget().availableGeometry().center()
    qr.moveCenter(cp)
    self.move(qr.topLeft())

  def closeEvent(self, event):
    reply = QMessageBox.question(self, 'Message',
                                 "Are you sure to quit?", QMessageBox.Yes |
                                 QMessageBox.No, QMessageBox.No)

    if reply == QMessageBox.Yes:
      event.accept()
    else:
      event.ignore()

if __name__ == '__main__':

  app = QApplication(sys.argv)
  ex = MainWindow()
  sys.exit(app.exec_())
